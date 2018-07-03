<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('alert-system')->table('users')->insert(
            [
                [
                    'name'          => 'Daniel Andres',
                    'email'         => 'daespinosag@unal.edu.co',
                    'password'      => encrypt('secret'),
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
