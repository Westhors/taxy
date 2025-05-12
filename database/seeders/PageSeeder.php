<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'user-help-support',
                'title_en' => 'Help and Support',
                'title_ar' => 'المساعدة والدعم',
                'content_en' => 'For assistance, please contact our support team at support@example.com.',
                'content_ar' => 'للمساعدة، يرجى التواصل مع فريق الدعم لدينا عبر البريد الإلكتروني support@example.com.',
            ],
            [
                'slug' => 'user-privacy-policy',
                'title_en' => 'Privacy Policy for Ride share',
                'title_ar' => 'سياسة الخصوصية',
                'content_en' => 'We value your privacy and ensure your data is protected.',
                'content_ar' => 'نحن نقدر خصوصيتك ونضمن حماية بياناتك.',
            ],
            [
                'slug' => 'user-about-us',
                'title_en' => 'About Us',
                'title_ar' => 'معلومات عنا',
                'content_en' => 'We are a company committed to delivering the best service.',
                'content_ar' => 'نحن شركة ملتزمة بتقديم أفضل خدمة.',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
