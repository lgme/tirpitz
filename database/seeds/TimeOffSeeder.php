<?php

use Illuminate\Database\Seeder;

class TimeOffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\TimeOff::class, 10)->create();
    }
}
