<?php

use App\Position;
use App\User;
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
        factory(Position::class, 1)->create()(function ($position) {
            // Seed the relation with one address
            $user = factory(User::class)->make();
            $position->position()->save($user);
        });
    }
}
