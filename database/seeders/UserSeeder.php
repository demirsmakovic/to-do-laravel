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
            'password' => "demir1234",
            'timezone' => "America/Jamaica"
        ],
        [
            'name' =>"Marko",
            'email' => "marko@gmail.com",
            'password' => "demir9234",
            'timezone' => "Africa/Tripoli"
        ],
        [
            'name' =>"Laravel",
            'email' => "demir.laravel@gmail.com",
            'password' => "demir1534",
            'timezone' => "Asia/Bahrain"
        ],
        [
            'name' =>"Neko",
            'email' => "neko@gmail.com",
            'password' => "demir1734",
            'timezone' => "Europe/Belgrade"
        ],
        [
            'name' =>"Lara",
            'email' => "lara@gmail.com",
            'password' => "demir1334",
            'timezone' => "Europe/Madrid"
        ],
    ];

    public function run()
    {
        foreach($this->users as $user) {
            DB::table('users')->insert([
                'name' => $user["name"],
                'email' => $user["email"],
                'password' => bcrypt($user["password"]),
                'timezone' => $user["timezone"],

            ]);
        }
    }
}