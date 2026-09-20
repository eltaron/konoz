<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;
use App\Support\SlugGenerator;
use App\Filament\Resources\CourseResource\RelationManagers\LessonsRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\SessionsRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\ExamsRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\FreeSessionsRelationManager;
use Filament\Schemas\Components\View;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $recordTitleAttribute = 'name_ar';

    // القواعد الصارمة للـ Type hints
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static string|\UnitEnum|null $navigationGroup = 'إدارة التعليم';

    protected static ?string $navigationLabel = 'الدورات';
    protected static ?string $pluralLabel = 'الدورات التدريبية';
    protected static ?string $label = 'دورة تعليمية';
    protected static ?int $navigationSort = 2;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name_ar', 'name_en', 'slug', 'instructor_ar'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('التحكم الكامل في الدورة')
                    ->description('إدارة المحتوى، المعلمين، والأسعار في مكان واحد')
                    ->icon('heroicon-m-cog-6-tooth')

                    ->schema([
                        Tabs::make('Course Management')
                            ->tabs([
                                // التبويب الأول: الهوية والبيانات الأساسية
                                TabsTab::make('البيانات الأساسية')
                                    ->icon('heroicon-m-identification')
                                    ->key('basic')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('name_ar')
                                                ->label('اسم الدورة (عربي)')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugGenerator::make($state, 'course')))
                                                ->columnSpanFull(),

                                            TextInput::make('name_en')
                                                ->label('اسم الدورة (English)')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', SlugGenerator::make($state, 'course')))
                                                ->columnSpanFull(),

                                            TextInput::make('slug')
                                                ->label('رابط الـ URL (Slug)')
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->disabled()
                                                ->dehydrated()
                                                ->prefix('courses/')
                                                ->columnSpanFull(),

                                            Select::make('category_id')
                                                ->label('التصنيف الدراسي')
                                                ->relationship('category', 'name_ar')
                                                ->searchable()
                                                ->preload()
                                                ->required(),

                                            ToggleButtons::make('icon')
                                                ->label('أيقونة الدورة')
                                                ->options(fn() => static::getFaIconOptions())
                                                // ->icons(fn() => static::getFaIconHtml())
                                                ->columns(5),
                                        ]),
                                    ]),

                                // التبويب الثاني: الأوصاف والمحتوى العلمي
                                TabsTab::make('الوصف العلمي')
                                    ->key('desc')
                                    ->icon('heroicon-m-document-text')
                                    ->schema([
                                        RichEditor::make('desc_ar')
                                            ->label('وصف الدورة (بالعربية)')
                                            ->columnSpanFull(),

                                        RichEditor::make('desc_en')
                                            ->label('وصف الدورة (English)')
                                            ->columnSpanFull(),

                                        Grid::make(3)->schema([
                                            Select::make('level')
                                                ->label('المستوى التعليمي')
                                                ->options([
                                                    'beginner' => 'مبتدئ',
                                                    'intermediate' => 'متوسط',
                                                    'advanced' => 'متقدم',
                                                ])->native(false),

                                            Select::make('audience')
                                                ->label('الفئة المستهدفة')
                                                ->options([
                                                    'women' => 'نساء',
                                                    'children' => 'أطفال',
                                                    'all' => 'الكل',
                                                ])->native(false),

                                            TextInput::make('duration')
                                                ->label('مدة الدورة'),
                                        ]),
                                    ]),

                                // التبويب الثالث: المعلمة والمسؤوليات
                                TabsTab::make('بيانات المعلمة')
                                    ->icon('heroicon-m-user-circle')
                                    ->key('teacher')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Select::make('user_id')
                                                ->label('المعلم المسؤول (الحساب التقني)')
                                                ->relationship('instructor', 'name', fn($query) => $query->where('role', 'teacher'))
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->columnSpanFull(),

                                            TextInput::make('instructor_ar')->label('اسم المعلمة الظاهري (عربي)'),
                                            TextInput::make('instructor_en')->label('اسم المعلمة الظاهري (English)'),

                                            RichEditor::make('instructor_bio_ar')
                                                ->label('السيرة الذاتية (عربي)')
                                                ->columnSpanFull(),

                                            RichEditor::make('instructor_bio_en')
                                                ->label('السيرة الذاتية (English)')
                                                ->columnSpanFull(),
                                        ]),
                                    ]),

                                // التبويب الرابع: الإعدادات المالية والوسائط
                                TabsTab::make('المالية والوسائط')
                                    ->key('finance')
                                    ->icon('heroicon-m-photo')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('price')
                                                ->label('سعر الدورة')
                                                ->numeric()
                                                ->default(0)
                                                ->prefix('$')
                                                ->placeholder('0.00')
                                                ->helperText('تُترك فارغة للدورة المجانية وتُحفظ 0 تلقائياً')
                                                ->disabled(fn(Get $get): bool => (bool) $get('is_free')),

                                            TextInput::make('students_count')
                                                ->label('عدد الطلاب المسجلين (يدوي)'),

                                            Toggle::make('is_free')
                                                ->label('دورة مجانية تماماً')
                                                ->onColor('success')
                                                ->inline(false),

                                            Toggle::make('is_active')
                                                ->label('تفعيل الدورة الآن')
                                                ->default(true)
                                                ->onColor('success')
                                                ->inline(false),

                                            FileUpload::make('image')
                                                ->label('صورة غلاف الدورة')
                                                ->image()
                                                ->imageEditor()
                                                ->disk('public')
                                                ->directory('courses-covers')
                                                ->columnSpanFull(),

                                        ]),
                                    ]),
                            ])->columnSpanFull(),
                    ])->columnSpanFull(),

                View::make('filament.schemas.components.course-tabs-nav')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الغلاف')
                    ->circular(),

                Tables\Columns\TextColumn::make('name_ar')
                    ->label('الدورة')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(Course $record): string => $record->name_en),

                Tables\Columns\TextColumn::make('category.name_ar')
                    ->label('القسم')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('USD')
                    ->sortable()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_free')
                    ->label('مجانية')
                    ->boolean()
                    ->trueIcon('heroicon-o-gift')
                    ->falseIcon('heroicon-o-currency-dollar'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('نشط'),

                Tables\Columns\TextColumn::make('students_count')
                    ->label('المسجلين')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('تصفية حسب القسم')
                    ->relationship('category', 'name_ar'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('حالة الدورة'),

                Tables\Filters\TernaryFilter::make('is_free')
                    ->label('نوع الدفع'),
            ])
            ->actions([
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()->color('info'),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات الدورة')
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            LessonsRelationManager::class,
            SessionsRelationManager::class,
            ExamsRelationManager::class,
            FreeSessionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getFaIconOptions(): array
    {
        return [
            'fas fa-book-quran' => 'القرآن الكريم',
            'fas fa-microphone-lines' => 'التلاوة',
            'fas fa-book-open' => 'الكتب',
            'fas fa-graduation-cap' => 'تعليم',
            'fas fa-pen-nib' => 'الخط',
            'fas fa-pen' => 'الكتابة',
            'fas fa-star' => 'متميز',
            'fas fa-medal' => 'تكريم',
            'fas fa-trophy' => 'مسابقات',
            'fas fa-chart-line' => 'إحصاءات',
            'fas fa-clock' => 'مواعيد',
            'fas fa-calendar-days' => 'تقويم',
            'fas fa-users' => 'مجموعات',
            'fas fa-user-graduate' => 'خريجون',
            'fas fa-chalkboard-user' => 'محاضرات',
            'fas fa-video' => 'دروس مسجلة',
            'fas fa-headphones' => 'استماع',
            'fas fa-ear-listen' => 'استيعاب',
            'fas fa-globe' => 'عالمي',
            'fas fa-language' => 'لغات',
            'fas fa-dice-d6' => 'تجويد',
            'fas fa-shield' => 'عقيدة',
            'fas fa-book' => 'مكتبة',
            'fas fa-mosque' => 'مسجد',
            'fas fa-kaaba' => 'الحج',
            'fas fa-pray' => 'الصلاة',
            'fas fa-hands-praying' => 'دعاء',
        ];
    }

    public static function getFaIconHtml(): array
    {
        return [
            'fas fa-book-quran' => new HtmlString('<i class="fas fa-solid fas fa-book-quran" style="font-size:1.5rem"></i>'),
            'fas fa-microphone-lines' => new HtmlString('<i class="fas fa-solid fas fa-microphone-lines" style="font-size:1.5rem"></i>'),
            'fas fa-book-open' => new HtmlString('<i class="fas fa-solid fas fa-book-open" style="font-size:1.5rem"></i>'),
            'fas fa-graduation-cap' => new HtmlString('<i class="fas fa-solid fas fa-graduation-cap" style="font-size:1.5rem"></i>'),
            'fas fa-pen-nib' => new HtmlString('<i class="fas fa-solid fas fa-pen-nib" style="font-size:1.5rem"></i>'),
            'fas fa-pen' => new HtmlString('<i class="fas fa-solid fas fa-pen" style="font-size:1.5rem"></i>'),
            'fas fa-star' => new HtmlString('<i class="fas fa-solid fas fa-star" style="font-size:1.5rem"></i>'),
            'fas fa-medal' => new HtmlString('<i class="fas fa-solid fas fa-medal" style="font-size:1.5rem"></i>'),
            'fas fa-trophy' => new HtmlString('<i class="fas fa-solid fas fa-trophy" style="font-size:1.5rem"></i>'),
            'fas fa-chart-line' => new HtmlString('<i class="fas fa-solid fas fa-chart-line" style="font-size:1.5rem"></i>'),
            'fas fa-clock' => new HtmlString('<i class="fas fa-solid fas fa-clock" style="font-size:1.5rem"></i>'),
            'fas fa-calendar-days' => new HtmlString('<i class="fas fa-solid fas fa-calendar-days" style="font-size:1.5rem"></i>'),
            'fas fa-users' => new HtmlString('<i class="fas fa-solid fas fa-users" style="font-size:1.5rem"></i>'),
            'fas fa-user-graduate' => new HtmlString('<i class="fas fa-solid fas fa-user-graduate" style="font-size:1.5rem"></i>'),
            'fas fa-chalkboard-user' => new HtmlString('<i class="fas fa-solid fas fa-chalkboard-user" style="font-size:1.5rem"></i>'),
            'fas fa-video' => new HtmlString('<i class="fas fa-solid fas fa-video" style="font-size:1.5rem"></i>'),
            'fas fa-headphones' => new HtmlString('<i class="fas fa-solid fas fa-headphones" style="font-size:1.5rem"></i>'),
            'fas fa-ear-listen' => new HtmlString('<i class="fas fa-solid fas fa-ear-listen" style="font-size:1.5rem"></i>'),
            'fas fa-globe' => new HtmlString('<i class="fas fa-solid fas fa-globe" style="font-size:1.5rem"></i>'),
            'fas fa-language' => new HtmlString('<i class="fas fa-solid fas fa-language" style="font-size:1.5rem"></i>'),
            'fas fa-dice-d6' => new HtmlString('<i class="fas fa-solid fas fa-dice-d6" style="font-size:1.5rem"></i>'),
            'fas fa-shield' => new HtmlString('<i class="fas fa-solid fas fa-shield" style="font-size:1.5rem"></i>'),
            'fas fa-book' => new HtmlString('<i class="fas fa-solid fas fa-book" style="font-size:1.5rem"></i>'),
            'fas fa-mosque' => new HtmlString('<i class="fas fa-solid fas fa-mosque" style="font-size:1.5rem"></i>'),
            'fas fa-kaaba' => new HtmlString('<i class="fas fa-solid fas fa-kaaba" style="font-size:1.5rem"></i>'),
            'fas fa-pray' => new HtmlString('<i class="fas fa-solid fas fa-pray" style="font-size:1.5rem"></i>'),
            'fas fa-hands-praying' => new HtmlString('<i class="fas fa-solid fas fa-hands-praying" style="font-size:1.5rem"></i>'),
        ];
    }
}
