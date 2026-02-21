<?php

namespace Database\Seeders;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\BranchWorkingHour;
use App\Models\Salon;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_SA'); // نستخدم بيانات عربية

        // 1. جلب الفرع الرئيسي الذي أنشأناه سابقاً
        $salon = Salon::first();
        if (! $salon) {
            return;
        }
        // 1. جلب الفرع الرئيسي الذي أنشأناه سابقاً
        $branch = Branch::first();
        if (! $branch) {
            return;
        }

        // 2. إنشاء ساعات العمل للفرع (طوال الأسبوع من 10 صباحاً لـ 10 مساءً)
        // نستخدم الأرقام من 0 إلى 6 لتطابق نوع الحقل (Integer) في قاعدة البيانات
        for ($day = 0; $day <= 6; $day++) {
            BranchWorkingHour::firstOrCreate([
                'branch_id' => $branch->id,
                'day_of_week' => $day,
            ], [
                'open_time' => '10:00:00',
                'close_time' => '22:00:00',
                'is_closed' => false,
            ]);
        }

        // 3. إنشاء خدمات حقيقية للصالون
        $servicesData = [
            ['name' => 'قص شعر كلاسيكي', 'price' => 100, 'duration_minutes' => 30],
            ['name' => 'حلاقة ذقن VIP', 'price' => 70, 'duration_minutes' => 20],
            ['name' => 'تنظيف بشرة عميق', 'price' => 250, 'duration_minutes' => 45],
            ['name' => 'بروتين وعناية بالشعر', 'price' => 500, 'duration_minutes' => 90],
            ['name' => 'باقة العريس كاملة', 'price' => 1200, 'duration_minutes' => 180],
        ];

        $services = [];
        foreach ($servicesData as $service) {
            $services[] = Service::firstOrCreate(
                ['name' => $service['name']],
                ['salon_id' => $salon->id, 'price' => $service['price'], 'duration_minutes' => $service['duration_minutes'], 'is_active' => true]
            );
        }

        // 4. إنشاء 3 موظفين (Staff) وإعطائهم الدور
        $staffMembers = [];
        for ($i = 1; $i <= 3; $i++) {
            $staff = User::firstOrCreate(
                ['email' => "staff{$i}@ahgzly.com"],
                [
                    'name' => 'الحلاق '.$faker->firstName,
                    'phone' => "0120000000{$i}",
                    'password' => Hash::make('password'),
                    'salon_id' => $salon->id,
                    'branch_id' => $branch->id,
                ]
            );
            $staff->assignRole('Staff Members');
            $staffMembers[] = $staff;
        }

        // 5. إنشاء 10 عملاء (Customers)
        $customers = [];
        for ($i = 1; $i <= 10; $i++) {
            $customer = User::firstOrCreate(
                ['email' => "customer{$i}@gmail.com"],
                [
                    'name' => $faker->name,
                    'phone' => "015000000{$i}",
                    'password' => Hash::make('password'),
                ]
            );
            $customer->assignRole('Customer');
            $customers[] = $customer;
        }

        // 6. توليد حجوزات (Appointments) وهمية للماضي والمستقبل لإشعال الإحصائيات!
        $statuses = [
            AppointmentStatus::PENDING,
            AppointmentStatus::CONFIRMED,
            AppointmentStatus::COMPLETED,
            AppointmentStatus::CANCELLED,
        ];

        // توليد 40 حجزاً موزعين على الأيام (10 أيام ماضية و 5 أيام قادمة)
        for ($i = 0; $i < 40; $i++) {
            $service = $faker->randomElement($services);
            $staff = $faker->randomElement($staffMembers);
            $customer = $faker->randomElement($customers);

            // اختيار يوم عشوائي بين 10 أيام مضت و 5 أيام قادمة
            $randomDayOffset = $faker->numberBetween(-10, 5);
            $appointmentDate = Carbon::today()->addDays($randomDayOffset);

            // وقت بداية عشوائي بين 12 ظهراً و 8 مساءً
            $startHour = $faker->numberBetween(12, 20);
            $startMinute = $faker->randomElement(['00', '30']);

            $startTime = Carbon::parse("{$appointmentDate->toDateString()} {$startHour}:{$startMinute}:00");
            $endTime = $startTime->copy()->addMinutes($service->duration);

            // تحديد الحالة: إذا كان الموعد في الماضي نجعله Completed، وإلا نختار عشوائياً
            $status = $randomDayOffset < 0 ? AppointmentStatus::COMPLETED : $faker->randomElement([AppointmentStatus::CONFIRMED, AppointmentStatus::PENDING]);

            Appointment::create([
                'branch_id' => $branch->id,
                'customer_id' => $customer->id,
                'employee_id' => $staff->id,
                'service_id' => $service->id,
                'appointment_date' => $appointmentDate->toDateString(),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'status' => $status,
                'total_price' => $service->price,
                'notes' => $faker->realText(30),
            ]);
        }
    }
}
