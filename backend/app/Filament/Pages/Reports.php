<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ReportStatsOverview;
use App\Models\Course;
use App\Support\ReportBuilder;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Reports extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'مركز التقارير الشامل';
    protected static ?string $title = 'التقارير والإحصائيات الشاملة';
    protected static ?string $slug = 'reports-center';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = true;

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
            Action::make('reset_filters')
                ->label('إعادة التعيين')
                ->icon('heroicon-m-arrow-path')
                ->color('gray')
                ->action('resetFilters'),

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
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'dateFrom', 'dateTo', 'status', 'courseId');
        $this->resetPage();
    }

    public function exportExcel(): void
    {
        // TODO: Implement Excel export functionality
        \Filament\Notifications\Notification::make()
            ->success()
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
        return ReportBuilder::query($this->reportType, $this->currentFilters());
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

    public function getHeaderWidgetsColumns(): int | array
    {
        return 4;
    }

    public function getReportSummaryProperty(): array
    {
        $results = $this->results;
        $type = $this->reportType;

        return match ($type) {
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

    /* ==================== Table integration ==================== */

    protected function currentFilters(): array
    {
        $filters = [
            'search' => $this->search,
            'from' => $this->dateFrom,
            'to' => $this->dateTo,
        ];

        if ($this->status !== '') {
            $filters['status'] = $this->status;
        }

        if ($this->courseId !== '') {
            $filters['course_id'] = $this->courseId;
        }

        return $filters;
    }

    protected function getTableQuery(): Builder
    {
        return ReportBuilder::queryBuilder($this->reportType, $this->currentFilters());
    }

    public function getTable(): Table
    {
        return $this->table($this->makeTable());
    }

    public function table(Table $table): Table
    {
        $columns = [];

        foreach (ReportBuilder::columns($this->reportType) as $index => $column) {
            $value = $column['value'];

            $columns[] = TextColumn::make($this->reportType . '_col_' . $index)
                ->label($column['label'])
                ->getStateUsing(fn (Model $record): mixed => $value($record))
                ->wrap();
        }

        return $table
            ->query(fn (): Builder => $this->getTableQuery())
            ->columns($columns)
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('إعدادات التقرير')
                    ->description('اختر نوع التقرير ثم استخدم الفلاتر للوصول إلى النتائج المطلوبة')
                    ->icon('heroicon-m-adjustments-horizontal')
                    ->schema([
                        Grid::make(6)->schema([
                            Select::make('reportType')
                                ->label('نوع التقرير')
                                ->options(fn (): array => ReportBuilder::types())
                                ->native(false)
                                ->live()
                                ->columnSpan(2),

                            TextInput::make('search')
                                ->label('بحث')
                                ->placeholder('اكتب للبحث في النتائج...')
                                ->prefixIcon('heroicon-m-magnifying-glass')
                                ->live(debounce: 600)
                                ->columnSpan(2),

                            Select::make('status')
                                ->label('الحالة')
                                ->options(fn (): array => $this->statusOptions ?? [])
                                ->visible(fn (): bool => filled($this->statusOptions))
                                ->native(false)
                                ->live()
                                ->columnSpan(1),

                            Select::make('courseId')
                                ->label('الدورة')
                                ->options(fn (): array => $this->courseOptions)
                                ->visible(fn (): bool => count($this->courseOptions) > 0)
                                ->native(false)
                                ->live()
                                ->columnSpan(1),
                        ]),

                        Grid::make(6)->schema([
                            DatePicker::make('dateFrom')
                                ->label('من تاريخ')
                                ->live()
                                ->columnSpan(2),

                            DatePicker::make('dateTo')
                                ->label('إلى تاريخ')
                                ->live()
                                ->columnSpan(2),
                        ]),
                    ]),

                Section::make('نتائج التقرير')
                    ->description(fn (): string => 'التقرير الحالي: ' . $this->typeLabel)
                    ->icon('heroicon-m-table-cells')
                    ->schema([
                        Grid::make(1)->schema([
                            EmbeddedTable::make(),
                        ]),
                    ]),
            ]);
    }
}