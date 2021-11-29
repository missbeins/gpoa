<?php

namespace Database\Seeders;

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
        $this->call([
            GenderSeeder::class,
            RoleSeeder::class,
            OrganizationTypeSeeder::class,
            AssetTypeSeeder::class,
            OrganizationSeeder::class,
            OrganizationAssetSeeder::class,
            CourseSeeder::class,

            UserSeeder::class,
            RoleUserSeeder::class,
        ]);
    }
            
}
