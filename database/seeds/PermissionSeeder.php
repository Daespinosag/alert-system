<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('alert-system')->table('permission')->insert(
            [
                [
                    'name'          => 'Permiso sobre indicador de deslizamiento',
                    'code'          => 'permission-a25',
                    'type'          => 'permission-alert',
                    'description'   => 'Permisos de acceso al indicador de deslizamientos a25',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'name'          => 'Permiso sobre indicador de inundación',
                    'code'          => 'permission-a10',
                    'type'          => 'permission-alert',
                    'description'   => 'Permisos de acceso al indicador de inundación a10',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]
            ]
        );
    }
}
