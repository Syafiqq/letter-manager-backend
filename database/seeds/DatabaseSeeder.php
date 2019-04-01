<?php

class DatabaseSeeder extends RollbackAbleSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        $this->withRollback(true)->call('LetterSeeder');
        $this->withRollback(true)->call('CouponSeeder');
        $this->withRollback(true)->call('UserStudentSeeder');

        $this->withRollback(false)->call('UserStudentSeeder');
        $this->withRollback(false)->call('CouponSeeder');
        $this->withRollback(false)->call('LetterSeeder');
    }

    function roll()
    {
        echo 'Do Nothing';
    }
}
