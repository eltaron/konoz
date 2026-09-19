<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Models\User;
use Filament\Actions;
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

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    protected static string|\UnitEnum|null $navigationGroup = 'إدارة النظام';
    protected static ?string $navigationLabel = 'المشرفين';
    protected static ?string $pluralLabel = 'المشرفين';
    protected static ?string $label = 'مشرف';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('role', 'admin')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('بيانات المشرف')
                ->description('إضافة أو تعديل حساب مشرف للدخول إلى لوحة التحكم')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('الاسم الكامل')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-user'),
                        TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-at-symbol'),
                        TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->revealable()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->helperText('اتركيها فارغة للإبقاء على كلمة المرور الحالية')
                            ->prefixIcon('heroicon-m-lock-closed')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->color('gray'),
                Tables\Columns\TextColumn::make('name')->label('اسم المشرف')->searchable()->sortable()->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->searchable()->copyable()->icon('heroicon-m-at-symbol'),
                Tables\Columns\TextColumn::make('last_login_at')->label('آخر دخول')->dateTime('Y/m/d H:i')->sortable()->placeholder('لم يدخل بعد'),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ الإضافة')->date('Y/m/d')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make()->color('info'),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
