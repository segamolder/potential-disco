<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleTagSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::factory()->count(20)->create();

        Article::all()->each(function (Article $article) use ($tags) {
            $article->tags()->attach($tags->random(random_int(1, 5))->pluck('id')->toArray());
        });
    }
}
