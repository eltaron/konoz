<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\Certificate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReportStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي الطلاب', Student::count())
                ->description('الطلاب المسجلين في النظام')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]),

            Stat::make('الدورات النشطة', Course::where('is_active', true)->count())
                ->description('دورات قيد التشغيل حالياً')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('متوسط درجات الطلاب', number_format(ExamResult::avg('score') ?? 0, 1) . '%')
                ->description('مستوى الأداء العام في الامتحانات')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('الشهادات المصدرة', Certificate::count())
                ->description('إجمالي الشهادات التي تم منحها')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}
