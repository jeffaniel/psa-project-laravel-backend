<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Grains',
                'slug' => 'grains',
                'description' => 'Rice, wheat, corn, oats, and other grain products',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Dairy & Eggs',
                'slug' => 'dairy-eggs',
                'description' => 'Milk, cheese, yogurt, butter, eggs, and dairy products',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Oils & Spices',
                'slug' => 'oils-spices',
                'description' => 'Cooking oils, spices, seasonings, and condiments',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Snacks & Beverages',
                'slug' => 'snacks-beverages',
                'description' => 'Chips, cookies, soft drinks, juices, and snack items',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Household Cleaning',
                'slug' => 'household-cleaning',
                'description' => 'Detergents, disinfectants, cleaning supplies, and household items',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Personal Care',
                'slug' => 'personal-care',
                'description' => 'Soaps, shampoos, lotions, toiletries, and personal hygiene products',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                array_merge($category, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('Categories seeded successfully!');
    }
}
