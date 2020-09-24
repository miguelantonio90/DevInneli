<?php

use App\Position;
use Illuminate\Database\Seeder;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = [
            [
                'key' => 'manager',
                'name' => 'Gerente',
                'accessEmail' => 1,
                'accessPin' => 1,
            ], [
                'key' => 'supervisor',
                'name' => 'Supervisor',
                'accessEmail' => 0,
                'accessPin' => 1,
            ], [
                'key' => 'atm',
                'name' => 'Cajero',
                'accessEmail' => 0,
                'accessPin' => 1,
            ], [
                'key' => 'waiter',
                'name' => 'Camarero',
                'accessEmail' => 0,
                'accessPin' => 1,
            ], [
                'key' => 'saller',
                'name' => 'Vendedor',
                'accessEmail' => 0,
                'accessPin' => 1,
            ],
        ];
        foreach ($positions as $position) {
            (new Position)->create($position)->save();
        }
    }
}
