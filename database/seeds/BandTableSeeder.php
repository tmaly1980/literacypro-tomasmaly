<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class BandTableSeeder extends Seeder {

    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $total_bands = 100;

        factory(App\Band::class, $total_bands)->create()->each(function ($band) {
	        $max_albums = 7;
	        
		    for($a = 0; $a < rand(1,$max_albums); $a++)
	        {
	        	$band->albums()->save(
	        		factory(App\Album::class)->make()
        		);
	        }
        });
    }

}