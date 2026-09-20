<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSliderResource\Pages;
use App\Models\HeroSlider;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class HeroSliderResource extends Resource
{
    protected static ?string $model = HeroSlider::class;

    protected static ?string $recordTitleAttribute = 'title_ar';

    // الالتزام بالـ Type hints المحددة
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static string|\UnitEnum|null $navigationGroup = 'واجهة الموقع';

    protected static ?string $navigationLabel = 'السلايدر';
    protected static ?string $pluralLabel = 'شرائح العرض (Slider)';
    protected static ?string $label = 'شريحة عرض';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)
                    ->schema([
                        // القسم الأول: النصوص والمحتوى (عربي وإنجليزي)
                        Section::make('محتوى الشريحة')
                            ->description('أدخل العناوين التي ستظهر فوق الصورة')
                            ->columnSpan(2)
                            ->aside()
                            ->icon('heroicon-m-pencil-square')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('title_ar')
                                            ->label('العنوان الرئيسي (عربي)')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        TextInput::make('title_en')
                                            ->label('العنوان الرئيسي (English)')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        TextInput::make('subtitle_ar')
                                            ->label('العنوان الفرعي (عربي)')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        TextInput::make('subtitle_en')
                                            ->label('العنوان الفرعي (English)')
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        TextInput::make('link')
                                            ->label('رابط الزر (URL)')
                                            ->placeholder('https://example.com/course/...')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // القسم الثاني: الصورة والإعدادات التقنية
                        Section::make('الوسائط والإعدادات')
                            ->description('ارفع الصورة واضبط ترتيب الظهور')
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('image')
                                    ->label('صورة السلايدر')
                                    ->image()
                                    ->disk('public')
                                    ->directory('hero-sliders')
                                    ->imageEditor()
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('sort_order')
                                    ->label('ترتيب العرض')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('الأرقام الأصغر تظهر أولاً')
                                    ->columnSpanFull(),

                                Toggle::make('is_published')
                                    ->label('حالة النشر')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline(false)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable()
                    ->badge(),

                ImageColumn::make('image')
                    ->label('الصورة')
                    ->square()
                    ->size(80),

                TextColumn::make('title_ar')
                    ->label('العنوان العربي')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(HeroSlider $record): string => $record->title_en ?? ''),

                ToggleColumn::make('is_published')
                    ->label('منشور'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc') // الترتيب الافتراضي حسب حقل الترتيب
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('حالة النشر'),
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
            ->striped()
            ->reorderable('sort_order'); // إمكانية إعادة الترتيب بالسحب والإفلات
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroSliders::route('/'),
            'create' => Pages\CreateHeroSlider::route('/create'),
            'edit' => Pages\EditHeroSlider::route('/{record}/edit'),
        ];
    }
}
