<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('role')->delete();
    
        Role::create(array(
            'role_id'     => 1,
            'rolename' => 'Admin'
        ));
    
        Role::create(array(
            'role_id'     => 2,
            'rolename' => 'Encoder'
        ));
    
        Role::create(array(
            'role_id'     => 3,
            'rolename' => 'Central Admin'
        ));
    
        Role::create(array(
            'role_id'     => 4,
            'rolename' => 'Regional'
        ));
    
        Role::create(array(
            'role_id'     => 5,
            'rolename' => 'Provincial'
        ));
    
        Role::create(array(
            'role_id'     => 6,
            'rolename' => 'Municipal'
        ));
    
        Role::create(array(
            'role_id'     => 7,
            'rolename' => 'SHC'
        ));
    
    }
}
