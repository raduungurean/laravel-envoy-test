<?php

use App\Location;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    public function run()
    {
        $l = new Location();
        $l->name = 'Pro Arena';
        $l->description = 'Pro Arena';
        $l->street = 'Calea Feldioarei 98';
        $l->city = 'Brasov';
        $l->state = 'Brasov';
        $l->country_id = '642';
        $l->website = 'http://pro-arena.ro/';
        $l->created_by = 1;
        $l->group_id = 1;
        $l->save();
    }
}
