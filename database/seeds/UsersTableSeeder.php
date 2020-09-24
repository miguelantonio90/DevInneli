<?php

use App\Position;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->create()(function ($user) {
            // Seed the relation with one address
            $position = factory(Position::class)->make();
            $user->positions()->save($position);
        });
    }
}
