<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'bdo',
            'email' => 'bdo@com.br',
            'password' => bcrypt('123456'),
            'image' => 'img/default.png'
        ]);
        User::create([
            'name' => 'Alice',
            'email' => 'alice@sao.com.br',
            'password' => bcrypt('123456'),
            'image' => 'img/default.png'
            ]);
        User::create([
            'name' => 'Kirito',
            'email' => 'kirito@sao.com.br',
            'password' => bcrypt('123456'),
            'image' => 'img/default.png'
            ]);
        User::create([
            'name' => 'Asuna',
            'email' => 'asuna@sao.com.br',
            'password' => bcrypt('123456'),
            'image' => 'img/default.png'
            ]);
    }
}
