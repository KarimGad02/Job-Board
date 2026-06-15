<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Technology;

class TaxonomySeeder extends Seeder
{
    public function run()
    {
        // Insert Categories
        $categories = ['Programming', 'Management', 'Design', 'Marketing', 'Customer Support'];
        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }

        // Insert Technologies
        $technologies = ['Laravel', 'Vue.js', 'React', 'Node.js', 'PHP', 'Python', 'Angular', 'Java'];
        foreach ($technologies as $tech) {
            Technology::firstOrCreate(['name' => $tech]);
        }
    }
}