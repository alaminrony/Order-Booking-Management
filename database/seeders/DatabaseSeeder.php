<?php

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\Department;
use App\Models\UserDepartment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
//        Buyer::factory(3)->create();
//        Department::factory(4)->create();
        UserDepartment::factory(5)->create();
    }
}
