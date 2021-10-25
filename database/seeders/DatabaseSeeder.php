<?php

namespace Database\Seeders;

use App\Models\Folder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Folder::create([
            'google_folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
            'parent_folder_id' => null,
            'name' => 'root',
        ]);
    }
}
