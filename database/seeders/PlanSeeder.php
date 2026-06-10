<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Service;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $membership = Service::where('name', 'Membership')->firstOrFail();
        $coaching   = Service::where('name', 'Coaching')->firstOrFail();

        $plans = [
            // Subscription plans
            [
                'service_id'  => $membership->id,
                'code'        => 'MBR-1M',
                'name'        => 'Bulanan',
                'description' => 'Keanggotaan 1 bulan.',
                'days'        => 30,
                'amount'      => 0,
                'status'      => 'active',
            ],
            [
                'service_id'  => $membership->id,
                'code'        => 'MBR-3M',
                'name'        => '3 Bulan',
                'description' => 'Keanggotaan 3 bulan.',
                'days'        => 90,
                'amount'      => 0,
                'status'      => 'active',
            ],
            [
                'service_id'  => $membership->id,
                'code'        => 'MBR-6M',
                'name'        => '6 Bulan',
                'description' => 'Keanggotaan 6 bulan.',
                'days'        => 180,
                'amount'      => 0,
                'status'      => 'active',
            ],
            [
                'service_id'  => $membership->id,
                'code'        => 'MBR-1Y',
                'name'        => 'Tahunan',
                'description' => 'Keanggotaan 1 tahun.',
                'days'        => 365,
                'amount'      => 0,
                'status'      => 'active',
            ],
            // Coaching plans
            [
                'service_id'  => $coaching->id,
                'code'        => 'CCH-1S',
                'name'        => 'Per Sesi',
                'description' => 'Satu sesi coaching.',
                'days'        => 1,
                'amount'      => 0,
                'status'      => 'active',
            ],
            [
                'service_id'  => $coaching->id,
                'code'        => 'CCH-4S',
                'name'        => 'Paket 4 Sesi',
                'description' => 'Paket 4 sesi coaching.',
                'days'        => 30,
                'amount'      => 0,
                'status'      => 'active',
            ],
            [
                'service_id'  => $coaching->id,
                'code'        => 'CCH-8S',
                'name'        => 'Paket 8 Sesi',
                'description' => 'Paket 8 sesi coaching.',
                'days'        => 60,
                'amount'      => 0,
                'status'      => 'active',
            ],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(
                ['code' => $plan['code']],
                $plan
            );
        }
    }
}
