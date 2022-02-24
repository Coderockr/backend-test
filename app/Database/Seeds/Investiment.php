<?php

namespace App\Database\Seeds;

use App\Models\InvestimentModel;
use CodeIgniter\Database\Seeder;

class Investiment extends Seeder
{
    public function run()
    {
        $investments = [
            'Título Público','CDB','Tesouro Direto','COE'
        ];

        $modelInvestiment = new InvestimentModel();
        $modelInvestiment->truncate();

        foreach ($investments as $index => $investment){

            $data = [
                'investment_name' => $investment,
                'investment_min_value' => mt_rand(100.00,300.00),
            ];
            try {
                $modelInvestiment->insert( $data);
            }catch (\Exception $e){
                echo $e->getMessage();
            };
        }
        //
    }
}
