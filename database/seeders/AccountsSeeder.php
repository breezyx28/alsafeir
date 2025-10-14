<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountsSeeder extends Seeder
{
    public function run()
    {
        $accounts = [
            ['code' => '1000', 'name' => 'الأصول', 'type' => 'asset', 'parent_code' => null],
            ['code' => '1100', 'name' => 'الصندوق', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1200', 'name' => 'البنك', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1300', 'name' => 'العملاء', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1400', 'name' => 'المخزون', 'type' => 'asset', 'parent_code' => '1000'],
            ['code' => '1410', 'name' => 'مخزون جاهز', 'type' => 'asset', 'parent_code' => '1400'],
            ['code' => '1420', 'name' => 'مخزون خام', 'type' => 'asset', 'parent_code' => '1400'],

            ['code' => '2000', 'name' => 'الخصوم', 'type' => 'liability', 'parent_code' => null],
            ['code' => '2100', 'name' => 'الموردين', 'type' => 'liability', 'parent_code' => '2000'],
            ['code' => '2200', 'name' => 'رواتب مستحقة', 'type' => 'liability', 'parent_code' => '2000'],
            ['code' => '2300', 'name' => 'مستحقات الترزية', 'type' => 'liability', 'parent_code' => '2000'],

            ['code' => '3000', 'name' => 'حقوق الملكية', 'type' => 'equity', 'parent_code' => null],
            ['code' => '3100', 'name' => 'رأس المال', 'type' => 'equity', 'parent_code' => '3000'],
            ['code' => '3200', 'name' => 'الأرباح المحتجزة', 'type' => 'equity', 'parent_code' => '3000'],

            ['code' => '4000', 'name' => 'الإيرادات', 'type' => 'revenue', 'parent_code' => null],
            ['code' => '4100', 'name' => 'مبيعات جاهزة', 'type' => 'revenue', 'parent_code' => '4000'],
            ['code' => '4200', 'name' => 'مبيعات تفصيل', 'type' => 'revenue', 'parent_code' => '4000'],
            ['code' => '4300', 'name' => 'إيرادات مشغل', 'type' => 'revenue', 'parent_code' => '4000'],

            ['code' => '5000', 'name' => 'المصروفات', 'type' => 'expense', 'parent_code' => null],
            ['code' => '5100', 'name' => 'مرتبات الموظفين', 'type' => 'expense', 'parent_code' => '5000'],
            ['code' => '5200', 'name' => 'حوافز الموظفين', 'type' => 'expense', 'parent_code' => '5000'],
            ['code' => '5300', 'name' => 'منصرفات تشغيل', 'type' => 'expense', 'parent_code' => '5000'],
            ['code' => '5400', 'name' => 'منصرفات مشغل', 'type' => 'expense', 'parent_code' => '5000'],
            ['code' => '5500', 'name' => 'إيجار', 'type' => 'expense', 'parent_code' => '5000'],
            ['code' => '5600', 'name' => 'كهرباء ومياه', 'type' => 'expense', 'parent_code' => '5000'],
            ['code' => '5700', 'name' => 'مردودات مبيعات', 'type' => 'expense', 'parent_code' => '5000'],
            ['code' => '5800', 'name' => 'استبدال المبيعات', 'type' => 'expense', 'parent_code' => '5000'],
        ];

        foreach ($accounts as $data) {
            $parentId = null;

            if ($data['parent_code']) {
                $parent = Account::where('code', $data['parent_code'])->first();
                if ($parent) {
                    $parentId = $parent->id;
                }
            }

            Account::firstOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'parent_id' => $parentId,
                ]
            );
        }
    }
}
