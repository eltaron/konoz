<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportCoursesFromCertificates extends Command
{
    protected $signature = 'app:import-courses-from-certificates';
    protected $description = 'Import course names from certificates directory into the courses table';

    public function handle()
    {
        $certPath = base_path('..\images\certificates');
        if (!is_dir($certPath)) {
            $this->error("Directory not found: $certPath");
            return 1;
        }

        $dirs = array_filter(scandir($certPath), fn($d) => !in_array($d, ['.', '..']));
        $categoryId = Category::first()?->id;
        if (!$categoryId) {
            $this->error('No categories found. Please seed categories first.');
            return 1;
        }

        $existing = Course::pluck('name_ar')->map(fn($n) => trim($n))->toArray();
        $count = 0;

        foreach ($dirs as $dir) {
            $name = trim($dir);
            if (in_array($name, $existing)) {
                $this->warn("Skipping existing: $name");
                continue;
            }

            $slug = Str::slug($name);
            if (Course::where('slug', $slug)->exists()) {
                $slug .= '-' . Str::random(4);
            }

            Course::create([
                'category_id' => $categoryId,
                'slug' => $slug,
                'name_ar' => $name,
                'name_en' => $name,
                'is_active' => true,
                'price' => 0,
                'is_free' => true,
            ]);

            $this->info("Created course: $name");
            $count++;
        }

        $this->info("Done! $count courses imported.");
        return 0;
    }
}
