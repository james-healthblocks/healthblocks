<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        Eloquent::unguard();
        $this->call(UserTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(SecurityQuestionTableSeeder::class);
        
        $this->call(InventorySeeder::class);
        $this->call(ServicesClientServiceSeeder::class);
        $this->call(TextSexSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(RiskGroupsSeeder::class);
        $this->call(STIDiagnosisSeeder::class);

        $this->call(FakeInventorySeeder::class);
        $this->call(FakeServicesSeeder::class);
        $this->call(FakeClients::class);
        // $this->call(FakeClinic::class);

        $this->call(STITestsTextSeeder::class);

        $this->call(HealthPortalSeeder::class);
    }
}


