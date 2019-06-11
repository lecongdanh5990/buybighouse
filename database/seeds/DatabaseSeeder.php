<?php

use Illuminate\Database\Seeder;
use App\Purpose;
use App\User;
use App\Target;

use App\Status;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        factory(User::class, 10)->create();
        factory(Purpose::class, 50)->create();
        factory(Target::class, 100)->create();
        factory(Status::class, 100)->create();
        //factory(Repeat::class, 100)->create();
    }
}
