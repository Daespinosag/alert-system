<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserPermisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('alert-system')->table('user_permissions')->insert(
            [
                [
                    'user_id'       => 1,
                    'permission_id' => 1,
                    'active'        => true,
                    'active_email'  => true,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'user_id'       => 1,
                    'permission_id' => 2,
                    'active'        => true,
                    'active_email'  => true,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
