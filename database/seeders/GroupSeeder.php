<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**+
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
            'id' => 100,
            'name' => ['en' => 'soups', 'ar' => 'الفطور'],
        ]);

        Group::create([
            'id' => 200,
            'name' => ['en' => 'fast food', 'ar' => 'الشوربات'],
        ]);

        Group::create([
            'id' => 300,
            'name' => ['en' => 'fast food', 'ar' => 'السلطات'],
        ]);

        Group::create([
            'id' => 400,
            'name' => ['en' => 'fast food', 'ar' => 'الوجبة الاولى'],
        ]);

        Group::create([
            'id' => 500,
            'name' => ['en' => 'fast food', 'ar' => 'الوجبة الثانية'],
        ]);

        Group::create([
            'id' => 600,
            'name' => ['en' => 'fast food', 'ar' => 'الوجبة الثالثة'],
        ]);
    }
}
