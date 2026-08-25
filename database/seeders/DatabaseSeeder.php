<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@jusoor.test'],
            [
                'name' => 'مدير النظام',
                'password' => 'password',
                'role' => 'admin',
                'city' => 'غزة',
            ]
        );

        $demoVolunteer = User::updateOrCreate(
            ['email' => 'volunteer@jusoor.test'],
            [
                'name' => 'أحمد المتطوع',
                'password' => 'password',
                'role' => 'volunteer',
                'city' => 'غزة',
                'phone' => '0599000001',
                'skills' => 'تدريس، تنظيم فعاليات، تصميم',
                'monthly_hours_goal' => 20,
            ]
        );

        $demoOrgUser = User::updateOrCreate(
            ['email' => 'org@jusoor.test'],
            [
                'name' => 'مؤسسة الأمل',
                'password' => 'password',
                'role' => 'organization',
                'city' => 'رام الله',
            ]
        );

        $demoOrg = Organization::updateOrCreate(
            ['user_id' => $demoOrgUser->id],
            [
                'name' => 'مؤسسة الأمل للتنمية المجتمعية',
                'description' => 'مؤسسة غير ربحية تسعى لتمكين الشباب وتنمية المجتمع المحلي من خلال برامج تطوعية متنوعة.',
                'website' => 'https://alamal.test',
                'city' => 'رام الله',
                'founded_year' => '2015',
                'verified' => true,
            ]
        );

        $categories = [
            ['name' => 'التعليم', 'slug' => 'education', 'icon' => '📚', 'color' => '#2E7D32'],
            ['name' => 'البيئة', 'slug' => 'environment', 'icon' => '🌳', 'color' => '#1B5E20'],
            ['name' => 'الصحة', 'slug' => 'health', 'icon' => '🏥', 'color' => '#C62828'],
            ['name' => 'الأطفال', 'slug' => 'children', 'icon' => '🧸', 'color' => '#F9A825'],
            ['name' => 'الإغاثة', 'slug' => 'relief', 'icon' => '🤝', 'color' => '#1E3A5F'],
            ['name' => 'التقنية', 'slug' => 'technology', 'icon' => '💻', 'color' => '#1565C0'],
        ];
        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        if (Opportunity::count() > 0 && User::where('role', 'volunteer')->count() > 1) {
            $this->command?->info('البيانات موجودة مسبقاً، تم تجاوز زرع البيانات التجريبية.');
            $this->printCredentials();

            return;
        }

        $volunteers = User::factory()->count(25)->create();
        $extraOrgs = Organization::factory()->count(4)->create();

        $opportunities = collect();
        for ($i = 0; $i < 12; $i++) {
            $opportunities->push(Opportunity::factory()->create([
                'organization_id' => $i % 2 === 0 ? $demoOrg->id : $extraOrgs->random()->id,
            ]));
        }

        $approvedUserIds = $volunteers->take(6)->pluck('id')->push($demoVolunteer->id);

        foreach ($opportunities->take(6) as $opp) {
            foreach ($approvedUserIds->random(mt_rand(2, 5)) as $uid) {
                Application::create([
                    'user_id' => $uid,
                    'opportunity_id' => $opp->id,
                    'status' => 'accepted',
                    'responded_at' => now()->subDays(5),
                ]);
                for ($p = 0; $p < mt_rand(1, 4); $p++) {
                    Participation::create([
                        'user_id' => $uid,
                        'opportunity_id' => $opp->id,
                        'hours' => mt_rand(2, 8),
                        'work_date' => now()->subDays(mt_rand(1, 150)),
                        'status' => 'approved',
                        'approved_by' => $demoOrg->user_id,
                        'approved_at' => now(),
                    ]);
                }
            }
            Notification::create([
                'user_id' => $demoOrg->user_id,
                'type' => 'application',
                'title' => 'طلب تطوع جديد',
                'body' => 'قام متطوع بالتقديم على فرصة ' . $opp->title,
                'action_url' => route('organization.applications'),
            ]);
        }

        foreach ($volunteers->take(3) as $v) {
            Notification::create([
                'user_id' => $v->id,
                'type' => 'application_response',
                'title' => 'تم قبول طلبك!',
                'body' => 'مبروك، تم قبولك للتطوع. راجع لوحة التحكم لمزيد من التفاصيل.',
                'action_url' => route('volunteer.dashboard'),
            ]);
        }

        Notification::create([
            'user_id' => $demoVolunteer->id,
            'type' => 'hours',
            'title' => 'تم إضافة ساعات تطوع',
            'body' => 'تم تسجيل 4 ساعات لك في إحدى الفرص.',
            'action_url' => route('volunteer.dashboard'),
        ]);

        $this->command?->info('تم زرع بيانات تجريبية بنجاح.');
        $this->printCredentials();
    }

    private function printCredentials(): void
    {
        $this->command?->info('أدمن: admin@jusoor.test / password');
        $this->command?->info('متطوع: volunteer@jusoor.test / password');
        $this->command?->info('مؤسسة: org@jusoor.test / password');
    }
}
