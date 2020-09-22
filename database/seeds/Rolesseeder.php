<?php

use Illuminate\Database\Seeder;

class Rolesseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'nombre'=>'administrador'
        ]);
    }
}
