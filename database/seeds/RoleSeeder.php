<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('alert-system')->table('role')->insert(
            [
                [
                    'name'          => 'Super Administrador',
                    'code'          => 'root',
                    'description'   => 'Administrador General del sistema',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'name'          => 'Administrador',
                    'code'          => 'admin',
                    'description'   => 'Administrador del sistema',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
                [
                    'name'          => 'Consultor',
                    'code'          => 'consult',
                    'description'   => 'Persona autorizada para realizar consultas en el sistema y ver los indicadores',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ],
            ]
        );
    }
}
