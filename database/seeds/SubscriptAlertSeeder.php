<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class SubscriptAlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('alert-system')->table('subscript-alert')->insert(
            [
                [
                    'user_id'       => 1,
                    'alert_code'    => 'alert-a25',
                    'active'        => true,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'user_id'       => 1,
                    'alert_code'    => 'alert-a10',
                    'active'        => true,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
