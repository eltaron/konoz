<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\CourseLesson;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LessonResource extends Resource
{
    protected static ?string $model = CourseLesson::class;

    protected static ?string $recordTitleAttribute = 'name_ar';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|\UnitEnum|null $navigationGroup = 'المحتوى التعليمي';

    protected static ?string $navigationLabel = 'الدروس';

    protected static ?string $pluralLabel = 'الدروس التعليمية';

    protected static ?string $label = 'درس';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('تفاصيل الدرس')
                    ->description('إدارة دروس الدورات وروابطها')
                    ->aside()
                    ->icon('heroicon-m-book-open')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('course_id')
                                    ->label('الدورة التابعة لها')
                                    ->relationship('course', 'name_ar')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('name_ar')
                                    ->label('اسم الدرس (عربي)')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('name_en')
                                    ->label('اسم الدرس (English)')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('order')
                                    ->label('الترتيب')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->helperText('يُعرض الدرس بالترتيب الأصغر أولاً'),

                                TextInput::make('link')
                                    ->label('رابط الدرس (YouTube / Zoom / ...)')
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->prefixIcon('heroicon-m-link')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('name_ar')
                    ->label('اسم الدرس')
                    ->searchable()
                    ->description(fn(CourseLesson $record): string => $record->course?->name_ar ?? ''),

                TextColumn::make('course_id')
                    ->label('الدورة')
                    ->formatStateUsing(fn($record) => $record->course?->name_ar ?? '—')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('link')
                    ->label('الرابط')
                    ->icon('heroicon-m-link')
                    ->color('primary')
                    ->limit(30)
                    ->copyable()
                    ->placeholder('—'),
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
                    Actions\Action::make('open_link')
                        ->label('فتح الدرس')
                        ->icon('heroicon-m-play')
                        ->color('success')
                        ->url(fn(CourseLesson $record): ?string => $record->link)
                        ->openUrlInNewTab()
                        ->visible(fn(CourseLesson $record): bool => !empty($record->link)),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات الدرس'),
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
