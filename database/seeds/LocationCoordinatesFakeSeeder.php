<?php

use Illuminate\Database\Seeder;

class LocationCoordinatesFakeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $numberOfRecords = 5000;
        $stepSize = round($numberOfRecords / 100);
        $percent = 0;
        for($i = 0; $i < $numberOfRecords; $i++) {
            factory(App\Models\Datamining\LocationCoordinates::class)->create();
            if ($i % $stepSize == 0) {
                print("[" . $percent . "%] created " . $i . "/" . $numberOfRecords . "\n");
                $percent++;
            }
        }
    }
}
