<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Blog;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Blog::class;


    public function definition(): array
    {
        $faker = \Faker\Factory::create('zh_CN');

        $chineseWords = ['测试', '示例', '内容', '数据', '文章', '开发', '程序', '代码', '技术', '学习'];

        // Make a "paragraph" by joining random Chinese words
        $paragraph = implode('', $faker->randomElements($chineseWords, 10));

        return [
            'title' => implode('', $faker->randomElements($chineseWords, 4)),
            'content' => $paragraph,
            'seo_title' => implode('', $faker->randomElements($chineseWords, 4)),
            'seo_keywords' => implode(',', $faker->randomElements($chineseWords, 3)),
            'seo_description' => implode('', $faker->randomElements($chineseWords, 5)),
            'subdomain_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }
}
