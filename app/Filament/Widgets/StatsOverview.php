<?php

namespace App\Filament\Widgets;

// نستخدم موديل الـ STI للعملاء
use App\Enums\AppointmentStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    // تحديد ترتيب ظهور الـ Widget في الصفحة (أعلى الصفحة)
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        // 1. التحقق من الصلاحية الأكبر (للمالك أو المدير)
        if ($user->can('view_revenue_stats')) {
            $totalRevenue = \App\Models\Appointment::whereIn('status', [AppointmentStatus::COMPLETED, AppointmentStatus::CONFIRMED])->sum('total_price');
            $todayAppointmentsCount = \App\Models\Appointment::whereDate('appointment_date', \Carbon\Carbon::today())->count();

            $stats[] = \Filament\Widgets\StatsOverviewWidget\Stat::make('إجمالي الإيرادات', number_format($totalRevenue, 2).' ج.م')
                ->description('إجمالي دخل المواعيد المؤكدة')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success');

            $stats[] = \Filament\Widgets\StatsOverviewWidget\Stat::make('حجوزات اليوم', $todayAppointmentsCount)
                ->description('مواعيد الصالون المجدولة لهذا اليوم')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning');

            $stats[] = \Filament\Widgets\StatsOverviewWidget\Stat::make('العملاء المسجلين', \App\Models\Customer::count())
                ->description('إجمالي قاعدة العملاء')
                ->descriptionIcon('heroicon-m-users')
                ->color('info');

        } elseif ($user->can('view_personal_stats')) {
            $myAppointments = \App\Models\Appointment::where('employee_id', $user->id)
                ->whereDate('appointment_date', \Carbon\Carbon::today())
                ->count();

            $stats[] = \Filament\Widgets\StatsOverviewWidget\Stat::make('مواعيدي اليوم', $myAppointments)
                ->description('عدد الحجوزات المسندة إليك اليوم')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info');

        } elseif ($user->can('view_personal_stats')) {
            $myAppointments = \App\Models\Appointment::where('employee_id', $user->id)
                ->whereDate('appointment_date', \Carbon\Carbon::today())
                ->count();

            $stats[] = \Filament\Widgets\StatsOverviewWidget\Stat::make('مواعيدي اليوم', $myAppointments)
                ->description('عدد الحجوزات المسندة إليك اليوم')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info');
        }

        return $stats;
    }
}
