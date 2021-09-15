<?php

namespace Database\Seeders;

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
        /*\DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => \Hash::make('password'),
        ]);  */
        $this->call([
            UserSeeder::class,
            QuestionSeeder::class,
            VoiceSeeder::class,
        ]);
    }
}
