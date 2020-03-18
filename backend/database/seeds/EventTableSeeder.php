<?php

use App\Model\Event;
use App\Model\EventTeam;
use Illuminate\Database\Seeder;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Event::class, 10)->create()
            ->each(function ($event) {
                $event->team()->saveMany(factory(EventTeam::class, 3)->make());
            });
    }
}
