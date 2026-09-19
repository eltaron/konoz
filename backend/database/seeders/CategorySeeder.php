<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'quran', 'name_ar' => 'القرآن الكريم', 'name_en' => 'Quran', 'icon' => 'fa-book-quran'],
            ['slug' => 'tajweed', 'name_ar' => 'أحكام التجويد', 'name_en' => 'Tajweed', 'icon' => 'fa-microphone-lines'],
            ['slug' => 'qiraat', 'name_ar' => 'القراءات العشر', 'name_en' => 'Qiraat', 'icon' => 'fa-book-open'],
            ['slug' => 'islamic', 'name_ar' => 'العلوم الشرعية', 'name_en' => 'Islamic Studies', 'icon' => 'fa-lightbulb'],
            ['slug' => 'services', 'name_ar' => 'خدمات', 'name_en' => 'Services', 'icon' => 'fa-hands-holding-child'],
        ];
        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
