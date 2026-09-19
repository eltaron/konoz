<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $recordTitleAttribute = 'name_ar';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static string|\UnitEnum|null $navigationGroup = 'الإعدادات العامة';
    protected static ?string $navigationLabel = 'طرق الدفع';
    protected static ?string $pluralLabel = 'طرق الدفع';
    protected static ?string $label = 'طريقة دفع';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('بيانات طريقة الدفع')
                    ->description('إدارة طرق الدفع المتاحة للطلاب')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name_ar')
                                ->label('الاسم (عربي)')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('مثال: فودافون كاش'),
                            TextInput::make('name_en')
                                ->label('الاسم (English)')
                                ->maxLength(255)
                                ->placeholder('Example: Vodafone Cash'),
                            TextInput::make('details_ar')
                                ->label('التفاصيل (عربي)')
                                ->maxLength(255)
                                ->placeholder('مثال: 0100 123 4567'),
                            TextInput::make('details_en')
                                ->label('التفاصيل (English)')
                                ->maxLength(255)
                                ->placeholder('Example: 0100 123 4567'),
                            Select::make('icon')
                                ->label('الأيقونة')
                                ->options([
                                    'fa-wallet' => '💰 محفظة',
                                    'fa-coins' => '🪙 عملات',
                                    'fa-credit-card' => '💳 بطاقة ائتمان',
                                    'fa-bank' => '🏦 بنك',
                                    'fa-paypal' => '💸 PayPal',
                                    'fa-mobile-screen' => '📱 محفظة جوال',
                                ])->native(false),
                            TextInput::make('identifier')
                                ->label('المعرف')
                                ->placeholder('vodafone, instapay, card')
                                ->helperText('معرف لاستخدامه في الكود'),
                            Toggle::make('is_active')
                                ->label('ظاهر على الموقع')
                                ->default(true)
                                ->onColor('success'),
                            TextInput::make('sort_order')
                                ->label('الترتيب')
                                ->numeric()
                                ->default(0),
                        ])->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('طريقة الدفع')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
                Tables\Columns\TextColumn::make('details')
                    ->label('التفاصيل')
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
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
