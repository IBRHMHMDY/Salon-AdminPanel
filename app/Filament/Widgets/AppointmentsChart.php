<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class AppointmentsChart extends ChartWidget
{
    protected ?string $heading = 'معدل الحجوزات (آخر 7 أيام)';

    protected static ?int $sort = 2; // ليظهر تحت الإحصائيات الرقمية

    protected function getData(): array
    {
        // إحصائية بسيطة لغرض الـ Demo (يمكنك تطويرها لاحقاً)
        return [
            'datasets' => [
                [
                    'label' => 'المواعيد المؤكدة',
                    'data' => [2, 5, 3, 7, 4, 8, 12], // بيانات تجريبية لشكل الرسم
                    'borderColor' => '#10b981', // لون أخضر
                ],
            ],
            'labels' => ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // نوع الرسم البياني (خطي)
    }

    // إخفاء الرسم عن الموظف العادي
    public static function canView(): bool
    {
        return auth()->user()->can('view_revenue_stats');
    }
}
