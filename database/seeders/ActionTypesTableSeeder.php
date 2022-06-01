<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('action_types')->truncate();

        DB::table('action_types')->insert([
            [
                'name' => 'viewed',
                'code' => 0,
                'description' => 'If a page, popup, video, pdf was "viewed" they will have an action index of 0',
            ],
            [
                'name' => 'started',
                'code' => 1,
                'description' => 'Mostly would be used for videos, an event has "started"',
            ],
            [
                'name' => 'ended',
                'code' => 2,
                'description' => 'Mostly would be used for videos, an event has "ended" and will no longer be running',
            ],
            [
                'name' => 'opened',
                'code' => 3,
                'description' => 'Mostly would be used for popups, could be user-opened or programatically-opened like a timeout.',
            ],
            [
                'name' => 'closed',
                'code' => 4,
                'description' => 'Mostly would be used for popups',
            ],
            [
                'name' => 'activated',
                'code' => 5,
                'description' => 'Mostly used for timeouts',
            ],
            [
                'name' => 'reset',
                'code' => 6,
                'description' => 'Mostly used for timeouts',
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
