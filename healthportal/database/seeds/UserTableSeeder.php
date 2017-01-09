<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('user')->delete();
    
        User::create(array(
            'name'     => 'HealthBlocks Admin',
            'email'    => 'admin@email.com',
            'password' => Hash::make('maroon'),
            'sq_id'    => 1,
            'answer' => 'luntian',            
            'role' => 1,
            'shc_id' => 1
            // 'api_token' => str_random(60)
        ));
    
        User::create(array(
            'name'     => 'HealthBlocks Encoder',
            'email'    => 'encoder@email.com',
            'password' => Hash::make('maroon'),
            'role' => 2,
            'shc_id' => 1
            // 'api_token' => str_random(60)
        ));
    
        User::create(array(
            'name'     => 'HealthBlocks Central Admin',
            'email'    => 'centraladmin@email.com',
            'password' => Hash::make('maroon'),
            'sq_id'    => 7,
            'answer' => 'jollibee',  
            'role' => 3,
            'shc_id' => 1
            // 'api_token' => str_random(60)
        ));
   
        User::create(array(
            'name'     => 'HealthBlocks Regional',
            'email'    => 'regional@email.com',
            'password' => Hash::make('maroon'),
            'role' => 4,
            'shc_id' => 1
            // 'api_token' => str_random(60)
        ));
   
        User::create(array(
            'name'     => 'HealthBlocks Provincial',
            'email'    => 'provincial@email.com',
            'password' => Hash::make('maroon'),
            'role' => 5,
            'shc_id' => 1
            // 'api_token' => str_random(60)
        ));
   
        User::create(array(
            'name'     => 'HealthBlocks Municipal',
            'email'    => 'municipal@email.com',
            'password' => Hash::make('maroon'),
            'role' => 6,
            'shc_id' => 1
            // 'api_token' => str_random(60)
        ));
   
	    User::create(array(
	    	'name'     => 'HealthBlocks SHC',
	        'email'    => 'shc@email.com',
            'password' => Hash::make('maroon'),
            'role' => 7,
            'shc_id' => 1
	        // 'api_token' => str_random(60)
	    ));
    }
}
