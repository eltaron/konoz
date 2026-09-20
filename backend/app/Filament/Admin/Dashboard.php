<?php

namespace App\Filament\Admin;

use App\Filament\Widgets\ReportStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'لوحة التحكم';

    protected static ?string $navigationLabel = 'لوحة التحكم';

    /**
     * @return array<class-string<\Filament\Widgets\Widget> | \Filament\Widgets\WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return collect(parent::getWidgets())
            ->filter(fn ($widget): bool => ! ($widget === ReportStatsOverview::class))
            ->values()
            ->all();
    }
}