<?php

namespace App\Filament\Widgets;

use App\Models\Session;
use Filament\Widgets\ChartWidget;

class SessionsChart extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';
    protected ?string $maxHeight = '280px';
    protected ?string $heading = 'الجلسات الأسبوعية';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = [];
        $counts = [];
        foreach (range(6, 0) as $i) {
            $date = now()->subDays($i);
            $days[] = $date->format('D');
            $counts[] = Session::whereDate('date', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'الجلسات',
                    'data' => $counts,
                    'backgroundColor' => 'rgba(15, 109, 128, 0.2)',
                    'borderColor' => '#0f6d80',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
