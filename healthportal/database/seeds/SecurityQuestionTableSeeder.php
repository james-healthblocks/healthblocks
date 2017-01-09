<?php

use Illuminate\Database\Seeder;
use App\SecurityQuestion;

class SecurityQuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('security_question')->delete();
    
        SecurityQuestion::create(array(
            'sq_id'     => 1,
            'question' => 'What is your favorite color?'
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 2,
            'question' => "What is your grandmother's middle name?"
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 3,
            'question' => 'What is the first name of the person you first kissed?'
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 4,
            'question' => 'What is the last name of the teacher who gave you your first failing grade?'
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 5,
            'question' => 'What time of the day were you born?'
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 6,
            'question' => 'What was the name of your elementary school?'
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 7,
            'question' => 'What was your favorite place to visit as a child?'
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 8,
            'question' => 'What is your childhood nickname?'
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 9,
            'question' => 'What is the name of your favorite childhood friend?'
        ));
    
        SecurityQuestion::create(array(
            'sq_id'     => 10,
            'question' => 'What is the name of the first beach you visited?'
        ));    
    }
}
