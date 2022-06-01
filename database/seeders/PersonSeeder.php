<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Person\Models\Person;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $query = Person::factory()
                    ->count(20);
        $query->create();
    }
}
