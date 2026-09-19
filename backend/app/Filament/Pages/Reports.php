<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ReportStatsOverview;
use App\Models\Course;
use App\Support\ReportBuilder;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class Reports extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string|\UnitEnum|null $navigationGroup = 'التقارير والإحصائيات';
    protected static ?string $navigationLabel = 'مركز التقارير الشامل';
    protected static ?string $title = 'التقارير والإحصائيات الشاملة';
    protected static ?string $slug = 'reports-center';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.pages.reports';

    public string $reportType = 'students';
    public string $search = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $status = '';
    public string $courseId = '';
    public string $exportFormat = 'pdf';

    public function mount(): void
    {
        $this->reportType = ReportBuilder::find((string) request()->query('type', 'students'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_pdf')
                ->label('تصدير PDF')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->url($this->printUrl('pdf'))
                ->openUrlInNewTab(),
            
            Action::make('export_excel')
                ->label('تصدير Excel')
                ->icon('heroicon-m-table-cells')
                ->color('info')
                ->action('exportExcel'),
            
            Action::make('print_report')
                ->label('طباعة التقرير')
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
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'dateFrom', 'dateTo', 'status', 'courseId');
    }

    public function exportExcel(): void
    {
        // TODO: Implement Excel export functionality
        notification()->success()
            ->title('جاري التصدير')
            ->body('سيتم تحميل ملف Excel قريباً')
            ->send();
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

    public function printUrl(string $format = 'pdf'): string
    {
        $params = ['type' => $this->reportType, 'format' => $format];
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

    public function getReportSummaryProperty(): array
    {
        $results = $this->results;
        $type = $this->reportType;
        
        return match($type) {
            'students' => [
                'total' => $results->count(),
                'active' => $results->where('status', 'active')->count(),
                'label' => 'إجمالي الطالبات',
            ],
            'courses' => [
                'total' => $results->count(),
                'active' => $results->where('is_active', true)->count(),
                'label' => 'إجمالي الدورات',
            ],
            'exams' => [
                'total' => $results->count(),
                'avg_score' => round($results->avg('avg_score') ?? 0, 1),
                'label' => 'إجمالي الامتحانات',
            ],
            'certificates' => [
                'total' => $results->count(),
                'issued' => $results->whereIn('status', ['issued', 'delivered'])->count(),
                'label' => 'إجمالي الشهادات',
            ],
            default => [
                'total' => $results->count(),
                'label' => 'الإجمالي',
            ],
        };
    }
}