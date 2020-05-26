<?php

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
        // $this->call(LaboratorySeeder::class);
        // $this->call(PermissionSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(CountriesSeeder::class);
        // $this->call(PatientSeeder::class);
        // $this->call(DemographicSeeder::class);
        // $this->call(SuspectCaseSeeder::class);
        // $this->call(ResidenceSeeder::class);
        // $this->call(RoomSeeder::class);
        $this->call(RegionsSeeder::class);
        $this->call(CommunesSeeder::class);
        $this->call(EstablishmentSeeder::class);
    }
}
