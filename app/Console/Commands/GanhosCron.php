<?php

namespace App\Console\Commands;

use App\Models\Investimento;
use App\Services\Ganhos;
use Illuminate\Console\Command;

class GanhosCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:ganhos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron que executa todos os dais
    e verifica se esta no dia que certo investimento foi criado caso sim,
     adiciona os ganhos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $investimentos = Investimento::all();
        foreach ($investimentos as $investimento){
            $investimentoDia = splitData($investimento->data)['dia'];
            $investimentoMes =  splitData($investimento->data)['mes'];
            $dataAtual = new \DateTime();
            $diaAtual = $dataAtual->format('d');
            $mesAtual = $dataAtual->format('m');
            if ($investimentoDia == $diaAtual && $investimentoMes == $mesAtual && $investimento->retirou == false){
                $service = new Ganhos($investimento);
                $ganho = $service->calcularGanhos();
                $investimento->update([
                    "ganhos" => $ganho["ganhos"]
                ]);
                $investimento->save();
            }
        }
        return 0;
    }
}
