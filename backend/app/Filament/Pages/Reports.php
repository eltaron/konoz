<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentCertificatesTable;
use App\Filament\Widgets\ReportStatsOverview;
use App\Models\Course;
use App\Support\ReportBuilder;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class Reports extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|\UnitEnum|null $navigationGroup = 'الشؤون التعليمية';
    protected static ?string $navigationLabel = 'مركز التقارير';
    protected static ?string $title = 'التقارير والإحصائيات الشاملة';
    protected static ?string $slug = 'reports';
    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.reports';

    public string $reportType = 'students';
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $status = '';
    public string $courseId = '';

    public function mount(): void
    {
        $this->reportType = ReportBuilder::find((string) request()->query('type'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('طباعة هذا التقرير')
                ->icon('heroicon-m-printer')
                ->color('primary')
                ->url($this->printUrl())
                ->openUrlInNewTab(),
        ];
    }

    public function updatedReportType(): void
    {
        $this->status = '';
        $this->courseId = '';
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'dateFrom', 'dateTo', 'status', 'courseId');
    }

    public function getTypesProperty(): array
    {
        return ReportBuilder::types();
    }

    public function getResultsProperty(): Collection
    {
        return ReportBuilder::query($this->reportType, [
            'search' => $this->search,
            'from' => $this->dateFrom,
            'to' => $this->dateTo,
            'status' => $this->status,
            'course_id' => $this->courseId,
        ]);
    }

    public function getColumnsProperty(): array
    {
        return ReportBuilder::columns($this->reportType);
    }

    public function getTypeLabelProperty(): string
    {
        return ReportBuilder::label($this->reportType);
    }

    public function getStatusOptionsProperty(): ?array
    {
        return ReportBuilder::statuses($this->reportType);
    }

    public function getCourseOptionsProperty(): array
    {
        if (! ReportBuilder::courseScoped($this->reportType)) {
            return [];
        }
        return Course::orderBy('name_ar')->pluck('name_ar', 'id')->all();
    }

    public function printUrl(): string
    {
        $params = ['type' => $this->reportType];
        if ($this->search !== '') {
            $params['search'] = $this->search;
        }
        if ($this->dateFrom !== '') {
            $params['from'] = $this->dateFrom;
        }
        if ($this->dateTo !== '') {
            $params['to'] = $this->dateTo;
        }
        if ($this->status !== '') {
            $params['status'] = $this->status;
        }
        if ($this->courseId !== '') {
            $params['course_id'] = $this->courseId;
        }
        return route('admin.reports.print', $params);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ReportStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }
}