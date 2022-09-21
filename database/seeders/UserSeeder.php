<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $users = [
        [
            'name' => "Demir",
            'email' => "demir.smakovic@gmail.com",
            'password' => "demir1234"
        ],
        [
            'name' =>"Marko",
            'email' => "marko@gmail.com",
            'password' => "demir9234"
        ],
        [
            'name' =>"Laravel",
            'email' => "demir.laravel@gmail.com",
            'password' => "demir1534"
        ],
        [
            'name' =>"Neko",
            'email' => "neko@gmail.com",
            'password' => "demir1734"
        ],
        [
            'name' =>"Lara",
            'email' => "lara@gmail.com",
            'password' => "demir1334"
        ],
    ];

    public function run()
    {
        foreach($this->users as $user) {
            DB::table('users')->insert([
                'name' => $user["name"],
                'email' => $user["email"],
                'password' => bcrypt($user["password"]),

            ]);
        }
    }
}