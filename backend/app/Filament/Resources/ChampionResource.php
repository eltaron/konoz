<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChampionResource\Pages;
use App\Models\Champion;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Builder;

class ChampionResource extends Resource
{
    protected static ?string $model = Champion::class;

    protected static ?string $recordTitleAttribute = 'name_ar';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-star';
    protected static string|\UnitEnum|null $navigationGroup = 'واجهة الموقع';
    protected static ?string $navigationLabel = 'النجوم';
    protected static ?string $pluralLabel = 'نجوم المنصة';
    protected static ?string $label = 'نجم';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('بيانات النجم')
                    ->description('أسماء وإنجازات الطلاب المتميزين')
                    ->schema([
                        TextInput::make('name_ar')
                            ->label('الاسم (عربي)')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name_en')
                            ->label('الاسم (English)')
                            ->maxLength(255),
                        TextInput::make('achievement_ar')
                            ->label('الإنجاز (عربي)')
                            ->placeholder('مثال: حافظة 5 أجزاء')
                            ->maxLength(255),
                        TextInput::make('achievement_en')
                            ->label('الإنجاز (English)')
                            ->maxLength(255),
                        Select::make('icon')
                            ->label('الأيقونة')
                            ->options([
                                'star' => '⭐ نجمة',
                                'medal' => '🏅 وسام',
                                'music' => '🎵 نوتة',
                                'users' => '👥 مستخدمين',
                                'trophy' => '🏆 كأس',
                                'crown' => '👑 تاج',
                            ])->native(false),
                        Toggle::make('is_active')
                            ->label('ظاهر على الموقع')
                            ->default(true)
                            ->onColor('success'),
                        TextInput::make('sort_order')
                            ->label('الترتيب')
                            ->numeric()
                            ->default(0),
                        FileUpload::make('image')
                            ->label('صورة الطالب')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('champions'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('achievement')
                    ->label('الإنجاز')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('إضافة نجم للمنصة'),
            ])
            ->actions([
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()->color('info'),
                    Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChampions::route('/'),
            'create' => Pages\CreateChampion::route('/create'),
            'edit' => Pages\EditChampion::route('/{record}/edit'),
        ];
    }
}
