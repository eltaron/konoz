<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TeacherResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static string|\UnitEnum|null $navigationGroup = 'الطلاب والكادر';
    protected static ?string $navigationLabel = 'المعلمات';
    protected static ?string $pluralLabel = 'المعلمات';
    protected static ?string $label = 'معلمة';
    protected static ?int $navigationSort = 2;

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
        return $schema
            ->columns(1)
            ->schema([
                Section::make('بيانات المعلمة')
                    ->description('إضافة أو تعديل حساب معلمة للدخول إلى لوحة تحكم المعلمات')
                    ->icon('heroicon-o-academic-cap')
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
                                ->helperText('اتركيها فارغة عند التعديل للإبقاء على كلمة المرور الحالية')
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
                    ->label('اسم المعلمة')
                    ->searchable(['name', 'email'])
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-m-academic-cap')
                    ->iconColor('info'),
                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-at-symbol'),
                TextColumn::make('courses_count')
                    ->label('الدورات')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('students_count')
                    ->label('الطالبات')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('last_login_at')
                    ->label('آخر دخول')
                    ->since()
                    ->sortable()
                    ->placeholder('لم تدخل بعد')
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
                Actions\DeleteAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ])
            ->striped();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'teacher')
            ->withCount([
                'courses',
                'courses as students_count' => fn(Builder $q) => $q
                    ->selectRaw('count(distinct course_student.student_id)')
                    ->join('course_student', 'course_student.course_id', '=', 'courses.id'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'view'   => Pages\ViewTeacher::route('/{record}'),
            'edit'   => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
