<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class TeacherResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static string|\UnitEnum|null $navigationGroup = 'إدارة المعلمات';
    protected static ?string $navigationLabel = 'المعلمات';
    protected static ?string $pluralLabel = 'المعلمات';
    protected static ?string $label = 'معلمة';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('role', 'teacher')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('بيانات المعلمة')
                ->description('إضافة أو تعديل حساب معلمة للدخول إلى لوحة التحكم')
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
                TextColumn::make('id')->label('ID')->sortable()->color('gray'),
                TextColumn::make('name')->label('اسم المعلمة')->searchable()->sortable()->weight(FontWeight::Bold),
                TextColumn::make('email')->label('البريد الإلكتروني')->searchable()->copyable()->icon('heroicon-m-at-symbol'),
                TextColumn::make('courses_count')->counts('courses')->label('عدد الدورات')->badge()->color('success'),
                TextColumn::make('last_login_at')->label('آخر دخول')->dateTime('Y/m/d H:i')->sortable()->placeholder('لم تدخل بعد'),
                TextColumn::make('created_at')->label('تاريخ الإضافة')->date('Y/m/d')->sortable(),
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
        return parent::getEloquentQuery()->where('role', 'teacher');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}