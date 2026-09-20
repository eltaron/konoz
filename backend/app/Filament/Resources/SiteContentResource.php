<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteContentResource\Pages;
use App\Models\SiteContent;
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
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class SiteContentResource extends Resource
{
    protected static ?string $model = SiteContent::class;

    protected static ?string $recordTitleAttribute = 'key';

    // الالتزام بالـ Type hints والمسارات المحددة
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static string|\UnitEnum|null $navigationGroup = 'واجهة الموقع';

    protected static ?string $navigationLabel = 'نصوص الصفحات';
    protected static ?string $pluralLabel = 'محتوى صفحات الموقع';
    protected static ?string $label = 'عنصر محتوى';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // السكشن الأول: التصنيف والهوية
                Section::make('تعريف عنصر المحتوى')
                    ->description('تحديد مكان ظهور النص في الموقع والمفتاح البرمجي الخاص به')
                    ->aside()
                    ->icon('heroicon-m-tag')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('key')
                                    ->label('المفتاح البرمجي (Key)')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('مثال: about_section_title')
                                    ->disabled(fn(?SiteContent $record) => $record !== null) // حماية المفتاح بعد الإنشاء
                                    ->columnSpanFull(), // استغلال المساحة كاملة

                                Select::make('group')
                                    ->label('مجموعة المحتوى (Group)')
                                    ->options([
                                        'general' => 'إعدادات عامة',
                                        'home' => 'الصفحة الرئيسية',
                                        'about' => 'صفحة من نحن',
                                        'footer' => 'التذييل (Footer)',
                                        'contact' => 'صفحة التواصل',
                                        'linguistics' => 'صفحة اللغات واللغويات',
                                        'parenting' => 'صفحة الدعم التربوي',
                                        'coding' => 'صفحة التكنولوجيا والبرمجة',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),

                // السكشن الثاني: النصوص المترجمة
                Section::make('المحتوى النصي')
                    ->description('أدخل المحتوى الذي سيظهر للزوار باللغتين')
                    ->aside()
                    ->icon('heroicon-m-language')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                RichEditor::make('value_ar')
                                    ->label('المحتوى (بالعربية)')
                                    ->placeholder('اكتب النص العربي هنا...')
                                    ->columnSpanFull(),

                                RichEditor::make('value_en')
                                    ->label('المحتوى (English)')
                                    ->placeholder('Enter English content here...')
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
                    ->label('المفتاح البرمجي')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->copyable(),

                TextColumn::make('group')
                    ->label('المجموعة')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('value_ar')
                    ->label('معاينة المحتوى العربي')
                    ->limit(50)
                    ->html() // لدعم عرض نصوص RichEditor بشكل صحيح
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('group', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('تصفية بالمجموعة')
                    ->options([
                        'general' => 'إعدادات عامة',
                        'home' => 'الصفحة الرئيسية',
                        'about' => 'صفحة من نحن',
                        'footer' => 'التذييل (Footer)',
                        'contact' => 'صفحة التواصل',
                        'linguistics' => 'صفحة اللغات واللغويات',
                        'parenting' => 'صفحة الدعم التربوي',
                        'coding' => 'صفحة التكنولوجيا والبرمجة',
                    ]),
            ])
            ->actions([
                // استخدام ActionGroup من Filament\Actions
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()->color('info'),
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
            'index' => Pages\ListSiteContents::route('/'),
            'create' => Pages\CreateSiteContent::route('/create'),
            'edit' => Pages\EditSiteContent::route('/{record}/edit'),
        ];
    }
}
