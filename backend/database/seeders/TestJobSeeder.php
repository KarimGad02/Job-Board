<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Job;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;

class TestJobSeeder extends Seeder
{
    public function run()
    {
                // 1. Create Employer
        $employer = User::create([
            'name' => 'Employee',
            'email' => 'employer@example.com',
            'password' => Hash::make('password')
        ]);
        $employerRole = Role::firstWhere('name', 'employer');
        if ($employerRole) {
            $employer->roles()->attach($employerRole->id);
        }

        // 2. Create Candidate
        $candidate = User::create([
            'name' => 'Mahmoud Ali',
            'email' => 'candidate@example.com',
            'password' => Hash::make('password')
        ]);
        $candidateRole = Role::firstWhere('name', 'candidate');
        if ($candidateRole) {
            $candidate->roles()->attach($candidateRole->id);
        }

        // 3. Fetch Category
        $progCategory = Category::firstWhere('name', 'Programming');
        $designCategory = Category::firstWhere('name', 'Design');

        // 4. Create Jobs
        $job1 = Job::create([
            'user_id' => $employer->id,
            'employer_id' => $employer->id,
            'category_id' => $progCategory->id,
            'title' => 'Senior Laravel Developer',
            'description' => 'We are seeking a Senior Laravel Developer with extensive PHP experience to build high-performance web applications.',
            'responsibilities' => "Write clean code.\nDevelop new features.\nLead junior developers.",
            'skills_and_qualifications' => "3+ years PHP/Laravel.\nStrong SQL skills.\nHTML/CSS/JS.",
            'salary_range' => '$80,000 - $100,000',
            'benefits' => 'Health insurance, remote options, flexible hours.',
            'location' => 'San Francisco, CA',
            'work_type' => 'hybrid',
            'status' => 'open'
        ]);

        $job2 = Job::create([
            'user_id' => $employer->id,
            'employer_id' => $employer->id,
            'category_id' => $progCategory->id,
            'title' => 'Vue.js Frontend Engineer',
            'description' => 'Looking for a passionate Vue.js developer to construct elegant UI/UX interfaces and state management.',
            'responsibilities' => "Build reusable components.\nOptimize frontend speed.\nCollaborate with UI designers.",
            'skills_and_qualifications' => "Vue.js (Composition API).\nCSS frameworks.\nGit.",
            'salary_range' => '$70,000 - $90,000',
            'benefits' => 'Flexible PTO, learning stipend.',
            'location' => 'Remote',
            'work_type' => 'remote',
            'status' => 'open'
        ]);

        $job3 = Job::create([
            'user_id' => $employer->id,
            'employer_id' => $employer->id,
            'category_id' => $designCategory->id,
            'title' => 'UI/UX Designer',
            'description' => 'Help us build beautiful designs, wireframes, and user experiences for our flagship job board application.',
            'responsibilities' => "Design wireframes.\nConduct user research.\nCollaborate with product managers.",
            'skills_and_qualifications' => "Figma proficiency.\nStrong UI portfolio.\nInteractive prototyping.",
            'salary_range' => '$65,000 - $80,000',
            'benefits' => 'Equity, health insurance.',
            'location' => 'New York, NY',
            'work_type' => 'onsite',
            'status' => 'open'
        ]);

        // 5. Create Comments
        Comment::create([
            'job_id' => $job1->id,
            'user_id' => $candidate->id,
            'text' => 'Is this role open to international applicants?',
            'status' => 'active'
        ]);

        Comment::create([
            'job_id' => $job1->id,
            'user_id' => $employer->id,
            'text' => 'Yes, we sponsor H1B visas for qualified candidates!',
            'status' => 'active'
        ]);
    }
}
