<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SessionResource\Pages;
use App\Models\Session; // تأكد من اسم الموديل الصحيح لجدول course_sessions
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;

class SessionResource extends Resource
{
    // تغيير الموديل ليتناسب مع جدول course_sessions
    protected static ?string $model = Session::class;

    protected static ?string $recordTitleAttribute = 'title_ar';

    // القواعد الصارمة للـ Type hints والمسارات
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-video-camera';
    protected static string|\UnitEnum|null $navigationGroup = 'المحتوى التعليمي';

    protected static ?string $navigationLabel = 'الجلسات';
    protected static ?string $pluralLabel = 'الجلسات التعليمية';
    protected static ?string $label = 'جلسة';
    protected static ?int $navigationSort = 3;

    // شارة ذكية للجلسات القادمة اليوم
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'upcoming')->whereDate('date', today())->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('تفاصيل الجلسة والبث')
                    ->description('إدارة مواعد الحصص، روابط البث المباشر، وبيانات الكويز')
                    ->aside()
                    ->icon('heroicon-m-presentation-chart-line')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // اختيار الدورة التدريبية
                                Select::make('course_id')
                                    ->label('الدورة التابعة لها')
                                    ->relationship('course', 'name_ar')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpanFull(),

                                // عناوين الجلسة
                                TextInput::make('title_ar')
                                    ->label('عنوان الجلسة (عربي)')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('title_en')
                                    ->label('عنوان الجلسة (English)')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                // التوقيت والتاريخ
                                DatePicker::make('date')
                                    ->label('تاريخ الجلسة')
                                    ->required()
                                    ->native(false),

                                Select::make('status')
                                    ->label('حالة الجلسة')
                                    ->options([
                                        'upcoming' => 'قادمة (لم تبدأ)',
                                        'completed' => 'مكتملة (انتهت)',
                                        'cancelled' => 'ملغاة',
                                    ])
                                    ->default('upcoming')
                                    ->native(false)
                                    ->required(),

                                TimePicker::make('time_from')
                                    ->label('وقت البدء')
                                    ->seconds(false)
                                    ->required(),

                                TimePicker::make('time_to')
                                    ->label('وقت الانتهاء')
                                    ->seconds(false)
                                    ->required(),

                                // رابط البث المباشر
                                TextInput::make('stream_url')
                                    ->label('رابط البث (Zoom / Google Meet)')
                                    ->url()
                                    ->placeholder('https://zoom.us/j/...')
                                    ->prefixIcon('heroicon-m-video-camera')
                                    ->columnSpanFull(), // استغلال المساحة كاملة

                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('Y/m/d')
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('time_from')
                    ->label('الوقت')
                    ->formatStateUsing(fn($record) => "{$record->time_from} - {$record->time_to}")
                    ->icon('heroicon-o-clock'),

                TextColumn::make('title_ar')
                    ->label('عنوان الجلسة')
                    ->searchable()
                    ->description(fn(Session $record): string => $record->course->name_ar),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'upcoming' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'upcoming' => 'قادمة',
                        'completed' => 'مكتملة',
                        'cancelled' => 'ملغاة',
                    }),

                TextColumn::make('stream_url')
                    ->label('الرابط')
                    ->icon('heroicon-m-link')
                    ->color('primary')
                    ->limit(20)
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('تصفية حسب الدورة')
                    ->relationship('course', 'name_ar'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة الجلسة')
                    ->options([
                        'upcoming' => 'قادمة',
                        'completed' => 'مكتملة',
                        'cancelled' => 'ملغاة',
                    ]),
            ])
            ->actions([
                // استخدام ActionGroup من Filament\Actions
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()->color('info'),
                    // إضافة أكشن للانتقال للبث المباشر فوراً
                    Actions\Action::make('go_to_stream')
                        ->label('فتح رابط البث')
                        ->icon('heroicon-m-video-camera')
                        ->color('success')
                        ->url(fn(Session $record): ?string => $record->stream_url)
                        ->openUrlInNewTab()
                        ->visible(fn(Session $record): bool => !empty($record->stream_url)),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات الجلسة')
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
            'index' => Pages\ListSessions::route('/'),
            'create' => Pages\CreateSession::route('/create'),
            'edit' => Pages\EditSession::route('/{record}/edit'),
        ];
    }
}
