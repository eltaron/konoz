<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use App\Support\SlugGenerator;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';
    protected static string|\UnitEnum|null $navigationGroup = 'المحتوى التعليمي';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'تصنيفات الدورات';
    protected static ?string $pluralLabel = 'الأقسام';
    protected static ?string $label = 'قسم';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return static::getModel()::count() > 5 ? 'success' : 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('معلومات القسم الأساسية')
                    ->description('أدخل تفاصيل القسم باللغتين لتحسين ظهورك في محركات البحث')
                    ->aside()
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name_ar')
                                    ->label('الاسم باللغة العربية')
                                    ->required()
                                    ->placeholder('مثال: علوم القرآن')
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugGenerator::make($state, 'category'))),

                                Forms\Components\TextInput::make('name_en')
                                    ->label('الاسم باللغة الإنجليزية')
                                    ->required()
                                    ->placeholder('Example: Quran Sciences')
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugGenerator::make($state, 'category'))),

                                Forms\Components\TextInput::make('slug')
                                    ->label('رابط القسم (Slug)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->prefix('courses/category/')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('الهوية والمظهر')
                    ->description('اختر الأيقونة التي ستظهر للطلاب في واجهة الموقع')
                    ->aside()
                    ->icon('heroicon-m-paint-brush')
                    ->schema([
                        Forms\Components\ToggleButtons::make('icon')
                            ->label('الأيقونة')
                            ->options(fn() => static::getFaIconOptions())
                            // ->icons(fn() => static::getFaIconHtml())
                            ->columns(5)
                            ->columnSpanFull(),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('name_ar')
                    ->label('القسم')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->description(fn(Category $record): string => $record->name_en),

                Tables\Columns\TextColumn::make('courses_count')
                    ->label('الدورات')
                    ->counts('courses')
                    ->sortable()
                    ->badge()
                    ->color(fn(int $state): string => $state > 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->form([
                        DatePicker::make('from')->label('من تاريخ'),
                        DatePicker::make('until')->label('إلى تاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) $indicators[] = 'من: ' . $data['from'];
                        if ($data['until'] ?? null) $indicators[] = 'إلى: ' . $data['until'];
                        return $indicators;
                    }),

                TernaryFilter::make('has_courses')
                    ->label('حالة المحتوى')
                    ->queries(
                        true: fn(Builder $query) => $query->whereHas('courses'),
                        false: fn(Builder $query) => $query->whereDoesntHave('courses'),
                    ),
            ])
            ->actions([
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()->color('info'),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-horizontal')
                    ->tooltip('إجراءات'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getFaIconOptions(): array
    {
        return [
            'fas fa-book-quran' => 'المصحف الشريف',
            'fas fa-microphone-lines' => 'التلاوة والتجويد',
            'fas fa-book-open' => 'الكتب والمقررات',
            'fas fa-lightbulb' => 'العلوم الشرعية',
            'fas fa-graduation-cap' => 'التعليم والشهادات',
            'fas fa-pen-nib' => 'الخط والإملاء',
            'fas fa-pen' => 'الكتابة والتعبير',
            'fas fa-feather-pointed' => 'الأدب والنصوص',
            'fas fa-star' => 'المتميزون',
            'fas fa-medal' => 'الجوائز والتكريم',
            'fas fa-trophy' => 'المسابقات',
            'fas fa-arrow-trend-up' => 'التقدم والتطور',
            'fas fa-chart-line' => 'الإحصائيات',
            'fas fa-clock' => 'المواعيد والجداول',
            'fas fa-calendar-days' => 'التقويم',
            'fas fa-users' => 'المجموعات',
            'fas fa-user-graduate' => 'الخريجون',
            'fas fa-chalkboard-user' => 'المحاضرات',
            'fas fa-video' => 'الدروس المسجلة',
            'fas fa-headphones' => 'الاستماع',
            'fas fa-ear-listen' => 'فهم واستيعاب',
            'fas fa-hand-holding-heart' => 'التربية والرعاية',
            'fas fa-heart' => 'القيم والأخلاق',
            'fas fa-hand-peace' => 'السلام والتسامح',
            'fas fa-globe' => 'الثقافة العالمية',
            'fas fa-language' => 'اللغات',
            'fas fa-dice-d6' => 'التجويد والأحكام',
            'fas fa-shield' => 'العقيدة',
            'fas fa-book' => 'المكتبة',
            'fas fa-mosque' => 'المسجد',
            'fas fa-kaaba' => 'الحج والعمرة',
            'fas fa-pray' => 'الصلاة',
            'fas fa-hands-praying' => 'الدعاء والذكر',
            'fas fa-quran' => 'القرآن الكريم (بديل)',
        ];
    }

    public static function getFaIconHtml(): array
    {
        return [
            'fas fa-book-quran' => new HtmlString('<i class="fas fa-solid fas fa-book-quran" style="font-size:1.5rem"></i>'),
            'fas fa-microphone-lines' => new HtmlString('<i class="fas fa-solid fas fa-microphone-lines" style="font-size:1.5rem"></i>'),
            'fas fa-book-open' => new HtmlString('<i class="fas fa-solid fas fa-book-open" style="font-size:1.5rem"></i>'),
            'fas fa-lightbulb' => new HtmlString('<i class="fas fa-solid fas fa-lightbulb" style="font-size:1.5rem"></i>'),
            'fas fa-graduation-cap' => new HtmlString('<i class="fas fa-solid fas fa-graduation-cap" style="font-size:1.5rem"></i>'),
            'fas fa-pen-nib' => new HtmlString('<i class="fas fa-solid fas fa-pen-nib" style="font-size:1.5rem"></i>'),
            'fas fa-pen' => new HtmlString('<i class="fas fa-solid fas fa-pen" style="font-size:1.5rem"></i>'),
            'fas fa-feather-pointed' => new HtmlString('<i class="fas fa-solid fas fa-feather-pointed" style="font-size:1.5rem"></i>'),
            'fas fa-star' => new HtmlString('<i class="fas fa-solid fas fa-star" style="font-size:1.5rem"></i>'),
            'fas fa-medal' => new HtmlString('<i class="fas fa-solid fas fa-medal" style="font-size:1.5rem"></i>'),
            'fas fa-trophy' => new HtmlString('<i class="fas fa-solid fas fa-trophy" style="font-size:1.5rem"></i>'),
            'fas fa-arrow-trend-up' => new HtmlString('<i class="fas fa-solid fas fa-arrow-trend-up" style="font-size:1.5rem"></i>'),
            'fas fa-chart-line' => new HtmlString('<i class="fas fa-solid fas fa-chart-line" style="font-size:1.5rem"></i>'),
            'fas fa-clock' => new HtmlString('<i class="fas fa-solid fas fa-clock" style="font-size:1.5rem"></i>'),
            'fas fa-calendar-days' => new HtmlString('<i class="fas fa-solid fas fa-calendar-days" style="font-size:1.5rem"></i>'),
            'fas fa-users' => new HtmlString('<i class="fas fa-solid fas fa-users" style="font-size:1.5rem"></i>'),
            'fas fa-user-graduate' => new HtmlString('<i class="fas fa-solid fas fa-user-graduate" style="font-size:1.5rem"></i>'),
            'fas fa-chalkboard-user' => new HtmlString('<i class="fas fa-solid fas fa-chalkboard-user" style="font-size:1.5rem"></i>'),
            'fas fa-video' => new HtmlString('<i class="fas fa-solid fas fa-video" style="font-size:1.5rem"></i>'),
            'fas fa-headphones' => new HtmlString('<i class="fas fa-solid fas fa-headphones" style="font-size:1.5rem"></i>'),
            'fas fa-ear-listen' => new HtmlString('<i class="fas fa-solid fas fa-ear-listen" style="font-size:1.5rem"></i>'),
            'fas fa-hand-holding-heart' => new HtmlString('<i class="fas fa-solid fas fa-hand-holding-heart" style="font-size:1.5rem"></i>'),
            'fas fa-heart' => new HtmlString('<i class="fas fa-solid fas fa-heart" style="font-size:1.5rem"></i>'),
            'fas fa-hand-peace' => new HtmlString('<i class="fas fa-solid fas fa-hand-peace" style="font-size:1.5rem"></i>'),
            'fas fa-globe' => new HtmlString('<i class="fas fa-solid fas fa-globe" style="font-size:1.5rem"></i>'),
            'fas fa-language' => new HtmlString('<i class="fas fa-solid fas fa-language" style="font-size:1.5rem"></i>'),
            'fas fa-dice-d6' => new HtmlString('<i class="fas fa-solid fas fa-dice-d6" style="font-size:1.5rem"></i>'),
            'fas fa-shield' => new HtmlString('<i class="fas fa-solid fas fa-shield" style="font-size:1.5rem"></i>'),
            'fas fa-book' => new HtmlString('<i class="fas fa-solid fas fa-book" style="font-size:1.5rem"></i>'),
            'fas fa-mosque' => new HtmlString('<i class="fas fa-solid fas fa-mosque" style="font-size:1.5rem"></i>'),
            'fas fa-kaaba' => new HtmlString('<i class="fas fa-solid fas fa-kaaba" style="font-size:1.5rem"></i>'),
            'fas fa-pray' => new HtmlString('<i class="fas fa-solid fas fa-pray" style="font-size:1.5rem"></i>'),
            'fas fa-hands-praying' => new HtmlString('<i class="fas fa-solid fas fa-hands-praying" style="font-size:1.5rem"></i>'),
            'fas fa-quran' => new HtmlString('<i class="fas fa-solid fas fa-quran" style="font-size:1.5rem"></i>'),
        ];
    }
}
