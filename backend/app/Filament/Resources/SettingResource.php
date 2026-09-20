<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
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
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $recordTitleAttribute = 'key';

    // القواعد الصارمة للـ Type hints والمسارات المطلوبة
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string|\UnitEnum|null $navigationGroup = 'الإعدادات';

    protected static ?string $navigationLabel = 'إعدادات الموقع';
    protected static ?string $pluralLabel = 'إعدادات النظام';
    protected static ?string $label = 'إعداد';
    protected static ?int $navigationSort = 1;

    public static function getGloballySearchableAttributes(): array
    {
        return ['key', 'value_ar', 'value_en'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('ضبط إعدادات النظام')
                    ->description('تحكم في القيم الثابتة التي تظهر في واجهات الموقع (مثل أرقام التواصل، العناوين، إلخ)')
                    ->aside() // التنسيق الجانبي المعتمد
                    ->icon('heroicon-m-adjustments-horizontal')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('key')
                                    ->label('مفتاح الإعداد (Key)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('مثال: contact_email')
                                    ->helperText('هذا المفتاح يستخدمه المبرمج لاستدعاء القيمة، يفضل عدم تعديله.')
                                    ->disabled(fn(?Setting $record) => $record !== null) // منع تعديل المفتاح بعد الإنشاء لضمان استقرار الموقع
                                    ->columnSpanFull(), // استغلال المساحة كاملة بنسبة 100%

                                Textarea::make('value_ar')
                                    ->label('القيمة باللغة العربية')
                                    ->placeholder('أدخل النص الظاهر للمستخدم بالعربية')
                                    ->rows(4)
                                    ->columnSpanFull(),

                                Textarea::make('value_en')
                                    ->label('القيمة باللغة الإنجليزية')
                                    ->placeholder('Enter the value in English')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('key')
                    ->label('مفتاح الإعداد')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->copyable() // إتاحة النسخ السريع للمفتاح لاستخدامه في الكود
                    ->copyMessage('تم نسخ المفتاح'),

                TextColumn::make('value_ar')
                    ->label('القيمة (عربي)')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn($record) => $record->value_ar),

                TextColumn::make('value_en')
                    ->label('القيمة (English)')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn($record) => $record->value_en),
            ])
            ->defaultSort('id', 'asc')
            ->actions([
                // استخدام ActionGroup من Filament\Actions
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()
                        ->label('تحديث القيمة')
                        ->color('info'),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات')
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
