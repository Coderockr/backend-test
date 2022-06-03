<?php

namespace App\Units\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DropSchema extends Command
{
    /**
     * Schemas default
     * 
     * @var array
     */
    private $schema_default = [
        "investment",
        "log",
        "public"
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:drop {name}';

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
        $schema_name = $this->argument('name');
        if($schema_name != "default"){
            DB::statement("DROP SCHEMA IF EXISTS $schema_name CASCADE;");
        }else{
            foreach ($this->schema_default as $item) {
                DB::statement("DROP SCHEMA IF EXISTS $item CASCADE;");
            }
        }
    }
}
