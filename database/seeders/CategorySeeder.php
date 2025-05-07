<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\User;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all users from the database
        $users = User::all();

        // Loop through each user and create categories for them
        foreach ($users as $user) {
            Category::create(['name' => 'Food', 'user_id' => $user->id]);
            Category::create(['name' => 'Transport', 'user_id' => $user->id]);
            Category::create(['name' => 'Entertainment', 'user_id' => $user->id]);
            Category::create(['name' => 'Shopping', 'user_id' => $user->id]);
            Category::create(['name' => 'Bills', 'user_id' => $user->id]);
            Category::create(['name' => 'Health', 'user_id' => $user->id]);
        }
    }
}
