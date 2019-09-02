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
                    'role_id'       => 3,
                    'name'          => 'Daniel Andres',
                    'institution'   => 'Universidad Nacional',
                    'email'         => 'daespinosag@unal.edu.co',
                    'confirmed'     => true,
                    'accepted'      => true,
                    'password'      => bcrypt('secret'),
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
