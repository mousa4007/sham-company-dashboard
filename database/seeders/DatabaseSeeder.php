<?php

namespace Database\Seeders;

use App\Models\AppUser;
use App\Models\StockedProduct;
use App\Models\WebApiKey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        \App\Models\User::factory(10)->create();

        // WebApiKey::factory()->create([
        //     'smsActivate_api_key' => '87f928c1639b2be22f8f3AAcd052A334',
        //     'vakSms_api_key' => '938f785b9f024a2aa74d6d354ed0dbca',
        //     'secondLine_api_key' => 'd0ccfaa3ae5471fb24ddaf00b1cdf458',
        // ]);

         \App\Models\User::factory()->create([
            'name' => 'mousa',
            'email' => 'mousa123@gmail.com',
            'password' => bcrypt('12341234'),
        ]);

        \App\Models\Category::create([
            'name' => 'أرقام وتساب',
            'description' => 'أرقام وتساب',
            'image_url' => 'https://res.cloudinary.com/wanis4007/image/upload/v1660860826/eg0mqfjp5adybhtz6zc6.jpg',
            'image_id' => 'eg0mqfjp5adybhtz6zc6',
            'arrangement' => 1
        ]);



        $this->call(LaratrustSeeder::class);
    }
}
