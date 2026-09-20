<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Session;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return [
            Stat::make('الدورات', Course::count())
                ->description('إجمالي الدورات المتاحة')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary')
                ->chart([7, 5, 8, 12, 10, 15, Course::count()]),

            Stat::make('الطلاب', Student::count())
                ->description(Student::where('status', 'active')->count() . ' نشط')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([10, 15, 12, 18, 20, 22, Student::count()]),

            Stat::make('الجلسات', Session::count())
                ->description(Session::where('status', 'completed')->count() . ' مكتملة')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('warning')
                ->chart([3, 5, 4, 6, 8, 5, Session::count()]),

            Stat::make('الامتحانات', Exam::count())
                ->description(Exam::where('date', '>=', now())->count() . ' قادمة')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('danger')
                ->chart([2, 3, 4, 3, 5, 4, Exam::count()]),

            Stat::make('الشهادات', Certificate::count())
                ->description(Certificate::whereIn('status', ['delivered', 'issued'])->count() . ' صادرة')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('gray')
                ->chart([1, 2, 1, 3, 2, 2, Certificate::count()]),
        ];
    }
}
