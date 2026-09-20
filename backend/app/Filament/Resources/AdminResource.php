<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    protected static string|\UnitEnum|null $navigationGroup = 'الإعدادات';
    protected static ?string $navigationLabel = 'المشرفين';
    protected static ?string $pluralLabel = 'المشرفين';
    protected static ?string $label = 'مشرف';
    protected static ?int $navigationSort = 3;

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
        return $schema
            ->columns(1)
            ->schema([
                Section::make('بيانات المشرف')
                    ->description('إضافة أو تعديل حساب مشرف للدخول إلى لوحة تحكم إدارة المنصة')
                    ->icon('heroicon-o-shield-check')
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
                                ->minLength(8)
                                ->required(fn(string $operation): bool => $operation === 'create')
                                ->dehydrated(fn($state): bool => filled($state))
                                ->helperText('اتركها فارغة عند التعديل للإبقاء على كلمة المرور الحالية')
                                ->prefixIcon('heroicon-m-lock-closed')
                                ->same('password_confirmation'),
                            TextInput::make('password_confirmation')
                                ->label('تأكيد كلمة المرور')
                                ->password()
                                ->revealable()
                                ->required(fn(string $operation): bool => $operation === 'create')
                                ->dehydrated(fn($state): bool => filled($state))
                                ->prefixIcon('heroicon-m-lock-closed'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->color('gray'),
                TextColumn::make('name')
                    ->label('اسم المشرف')
                    ->searchable(['name', 'email'])
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-m-shield-check')
                    ->iconColor('danger'),
                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-at-symbol'),
                TextColumn::make('last_login_at')
                    ->label('آخر دخول')
                    ->since()
                    ->sortable()
                    ->placeholder('لم يدخل بعد')
                    ->color(fn($state) => blank($state) ? 'gray' : 'success'),
                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->date('Y/m/d')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Actions\ViewAction::make()->color('info'),
                Actions\EditAction::make()->color('info'),
                Actions\DeleteAction::make()
                    ->visible(fn(User $record): bool => $record->id !== auth()->id())
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->visible(fn(): bool => auth()->user()?->role === 'admin')
                        ->requiresConfirmation(),
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
            'index'  => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'view'   => Pages\ViewAdmin::route('/{record}'),
            'edit'   => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
