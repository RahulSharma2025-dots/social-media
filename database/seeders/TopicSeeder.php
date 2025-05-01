<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use Illuminate\Support\Str;

class TopicSeeder extends Seeder
{
    public function run()
    {
        $topics = [
            'Technology' => 'Latest tech news and discussions',
            'Fashion' => 'Fashion trends and style tips',
            'Fitness' => 'Health and fitness advice',
            'Food' => 'Cooking and recipes',
            'Travel' => 'Travel experiences and tips',
            'Music' => 'Music news and reviews',
            'Art' => 'Art and creativity',
            'Business' => 'Business and entrepreneurship',
            'Education' => 'Learning and development',
            'Entertainment' => 'Movies, TV shows, and more'
        ];

        foreach ($topics as $name => $description) {
            Topic::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description
            ]);
        }
    }
} 