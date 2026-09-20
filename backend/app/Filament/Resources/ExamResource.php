<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Models\Exam;
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
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ExamResource\RelationManagers\ExamResultsRelationManager;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $recordTitleAttribute = 'title_ar';

    // القواعد الصارمة للـ Type hints والمسارات
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static string|\UnitEnum|null $navigationGroup = 'إدارة التعليم';

    protected static ?string $navigationLabel = 'الامتحانات';
    protected static ?string $pluralLabel = 'الامتحانات';
    protected static ?string $label = 'امتحان';
    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('إعداد الامتحان الشامل')
                    ->description('قم بضبط بيانات الامتحان وإضافة الأسئلة وتحديد الإجابات الصحيحة')
                    ->icon('heroicon-m-clipboard-document-list')
                    ->schema([
                        Tabs::make('Exam Details')
                            ->tabs([
                                // التبويب الأول: المعلومات الأساسية
                                TabsTab::make('بيانات الامتحان')
                                    ->icon('heroicon-m-information-circle')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Select::make('course_id')
                                                ->label('الدورة التابع لها')
                                                ->relationship('course', 'name_ar')
                                                ->searchable()
                                                ->preload()
                                                ->required(),

                                            DatePicker::make('date')
                                                ->label('تاريخ الامتحان')
                                                ->default(now())
                                                ->required(),

                                            TextInput::make('title_ar')
                                                ->label('عنوان الامتحان (عربي)')
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpanFull(),

                                            TextInput::make('title_en')
                                                ->label('عنوان الامتحان (English)')
                                                ->maxLength(255)
                                                ->columnSpanFull(),

                                            Grid::make(2)->schema([
                                                TextInput::make('total_students')
                                                    ->label('إجمالي الطلاب المستهدفين')
                                                    ->numeric()
                                                    ->disabled() // يتم تحديثه تلقائياً
                                                    ->placeholder('0'),

                                                TextInput::make('avg_score')
                                                    ->label('متوسط درجات الطلاب')
                                                    ->numeric()
                                                    ->suffix('%')
                                                    ->disabled()
                                                    ->placeholder('0.00'),
                                            ]),
                                        ]),
                                    ]),

                                // التبويب الثاني: بنك الأسئلة (Repeater)
                                TabsTab::make('أسئلة الامتحان')
                                    ->icon('heroicon-m-list-bullet')
                                    ->schema([
                                        Repeater::make('questions') // علاقة الامتحان بالأسئلة
                                            ->relationship('questions')
                                            ->label('قائمة الأسئلة')
                                            ->schema([
                                                Grid::make(2)->schema([
                                                    Textarea::make('question_ar')
                                                        ->label('السؤال (عربي)')
                                                        ->required()
                                                        ->rows(2)
                                                        ->columnSpanFull(),

                                                    Textarea::make('question_en')
                                                        ->label('السؤال (English)')
                                                        ->rows(2)
                                                        ->columnSpanFull(),

                                                    // نظام الخيارات كـ JSON
                                                    Repeater::make('options')
                                                        ->label('خيارات الإجابة')
                                                        ->schema([
                                                            TextInput::make('option_text')
                                                                ->label('نص الخيار')
                                                                ->required()
                                                                ->columnSpanFull(),
                                                        ])
                                                        ->grid(2)
                                                        ->addActionLabel('إضافة خيار جديد')
                                                        ->columnSpanFull(),

                                                    TextInput::make('correct_answer')
                                                        ->label('الإجابة الصحيحة')
                                                        ->placeholder('اكتب النص المطابق تماماً للإجابة الصحيحة')
                                                        ->required()
                                                        ->columnSpanFull(),

                                                    TextInput::make('order')
                                                        ->label('ترتيب السؤال')
                                                        ->numeric()
                                                        ->default(0),
                                                ]),
                                            ])
                                            ->itemLabel(fn(array $state): ?string => $state['question_ar'] ?? 'سؤال جديد')
                                            ->collapsible() // إمكانية طي الأسئلة لسهولة التصفح
                                            ->collapsed()
                                            ->cloneable() // إمكانية نسخ السؤال
                                            ->addActionLabel('إضافة سؤال للامتحان')
                                            ->columnSpanFull(),
                                    ]),
                            ])->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title_ar')
                    ->label('عنوان الامتحان')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(Exam $record): string => $record->title_en ?? ''),

                TextColumn::make('course.name_ar')
                    ->label('الدورة')
                    ->badge()
                    ->color('info'),

                TextColumn::make('questions_count')
                    ->label('عدد الأسئلة')
                    ->counts('questions')
                    ->badge(),

                TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('Y/m/d')
                    ->sortable(),

                TextColumn::make('avg_score')
                    ->label('المتوسط')
                    ->suffix('%')
                    ->color('success')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('تصفية حسب الدورة')
                    ->relationship('course', 'name_ar'),
            ])
            ->actions([
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()->color('info'),
                    // إضافة أكشن سريع لعرض النتائج
                    Actions\Action::make('results')
                        ->label('عرض نتائج الطلاب')
                        ->icon('heroicon-m-academic-cap')
                        ->color('success')
                        ->url(fn(Exam $record): string => self::getUrl('index') . "?tableFilters[exam_id][value]={$record->id}"),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات الامتحان')
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
            ExamResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }
}
