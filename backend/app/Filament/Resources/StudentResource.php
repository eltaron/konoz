<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers\AttendanceRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\CertificatesRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\CoursesRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\JuzProgressRelationManager;
use App\Models\Student;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $recordTitleAttribute = 'name_ar';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|\UnitEnum|null $navigationGroup = 'الطلاب والكادر';
    protected static ?string $navigationLabel = 'الطالبات';
    protected static ?string $pluralLabel = 'الطالبات';
    protected static ?string $label = 'طالبة';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('gender', 'female')->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('الملف الشخصي للطالبة')
                    ->description('إدارة البيانات الشخصية والحساب والحالة الدراسية للطالبة')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Tabs::make('Student Data')
                            ->tabs([
                                Tab::make('البيانات الأساسية')
                                    ->icon('heroicon-m-user')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('name_ar')
                                                ->label('الاسم الكامل (عربي)')
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpanFull(),
                                            TextInput::make('name_en')
                                                ->label('الاسم الكامل (English)')
                                                ->maxLength(255)
                                                ->columnSpanFull(),
                                            Select::make('gender')
                                                ->label('الجنس')
                                                ->options([
                                                    'male' => 'ذكر',
                                                    'female' => 'أنثى',
                                                ])
                                                ->default('female')
                                                ->native(false)
                                                ->required(),
                                            TextInput::make('age')
                                                ->label('العمر')
                                                ->numeric()
                                                ->minValue(3)
                                                ->maxValue(90)
                                                ->prefixIcon('heroicon-m-calendar'),
                                        ]),
                                    ]),

                                Tab::make('معلومات التواصل والحساب')
                                    ->icon('heroicon-m-phone')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('email')
                                                ->label('البريد الإلكتروني')
                                                ->email()
                                                ->prefixIcon('heroicon-m-at-symbol'),
                                            TextInput::make('phone')
                                                ->label('رقم الجوال / واتساب')
                                                ->tel()
                                                ->prefixIcon('heroicon-m-phone'),

                                            Toggle::make('create_user')
                                                ->label('إنشاء حساب تسجيل دخول')
                                                ->default(false)
                                                ->live()
                                                ->helperText('فعّلي هذا الخيار لإنشاء حساب دخول للطالبة أو لتحديث بيانات حسابها')
                                                ->columnSpanFull(),

                                            TextInput::make('user_email')
                                                ->label('البريد الإلكتروني للحساب')
                                                ->email()
                                                ->visible(fn ($get): bool => (bool) $get('create_user'))
                                                ->required(fn ($get): bool => (bool) $get('create_user'))
                                                ->prefixIcon('heroicon-m-at-symbol'),

                                            TextInput::make('user_password')
                                                ->label('كلمة المرور للحساب')
                                                ->password()
                                                ->revealable()
                                                ->minLength(8)
                                                ->visible(fn ($get): bool => (bool) $get('create_user'))
                                                ->helperText('اتركيها فارغة عند الإنشاء ليتم توليد كلمة مرور تلقائية')
                                                ->dehydrated(fn ($state): bool => filled($state))
                                                ->prefixIcon('heroicon-m-lock-closed'),

                                            Select::make('user_id')
                                                ->label('أو ربط بحساب موجود مسبقاً')
                                                ->relationship('user', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->visible(fn ($get): bool => ! (bool) $get('create_user'))
                                                ->helperText('اربطي الطالبة بحساب مستخدم موجود في النظام')
                                                ->columnSpanFull(),
                                        ]),
                                    ]),

                                Tab::make('الوضع الأكاديمي')
                                    ->icon('heroicon-m-academic-cap')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('level')
                                                ->label('المستوى الحالي')
                                                ->placeholder('مثال: المستوى الثالث'),
                                            Select::make('status')
                                                ->label('حالة الطالبة')
                                                ->options([
                                                    'active' => 'نشطة',
                                                    'suspended' => 'معلقة',
                                                    'inactive' => 'غير نشطة',
                                                ])
                                                ->default('active')
                                                ->required()
                                                ->native(false),
                                            DatePicker::make('joined_at')
                                                ->label('تاريخ الالتحاق')
                                                ->default(now())
                                                ->columnSpanFull(),
                                        ]),
                                    ]),
                            ])->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->color('gray'),

                TextColumn::make('name_ar')
                    ->label('اسم الطالبة')
                    ->searchable(['name_ar', 'name_en', 'phone'])
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn (Student $record): string => $record->name_en ?? ''),

                TextColumn::make('phone')
                    ->label('الجوال')
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                TextColumn::make('user.email')
                    ->label('الحساب')
                    ->icon(fn ($state) => filled($state) ? 'heroicon-m-check-circle' : 'heroicon-m-minus-circle')
                    ->iconColor(fn ($state) => filled($state) ? 'success' : 'gray')
                    ->placeholder('بدون حساب')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'warning',
                        'inactive' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'نشطة',
                        'suspended' => 'معلقة',
                        'inactive' => 'غير نشطة',
                    }),

                TextColumn::make('level')->label('المستوى')->toggleable(),

                TextColumn::make('enrollmentRequests_count')
                    ->counts('enrollmentRequests')
                    ->label('طلبات التسجيل')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('certificates_count')
                    ->counts('certificates')
                    ->label('الشهادات')
                    ->badge()
                    ->color('success'),

                TextColumn::make('joined_at')
                    ->label('تاريخ الانضمام')
                    ->date('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('تصفية بالحالة')
                    ->options([
                        'active' => 'نشطة',
                        'suspended' => 'معلقة',
                        'inactive' => 'غير نشطة',
                    ]),
                SelectFilter::make('gender')
                    ->label('الجنس')
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    ]),
                TernaryFilter::make('user_id')
                    ->label('تمتلك حساب دخول')
                    ->trueLabel('نعم')
                    ->falseLabel('لا')
                    ->placeholder('الكل')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('user_id'),
                        false: fn (Builder $query) => $query->whereNull('user_id'),
                    ),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()->color('info'),
                    EditAction::make()->color('info'),
                    DeleteAction::make()->requiresConfirmation(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات الطالبة'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('gender', 'female')
            ->withCount(['enrollmentRequests', 'certificates']);
    }

    public static function getRelations(): array
    {
        return [
            AttendanceRelationManager::class,
            JuzProgressRelationManager::class,
            CertificatesRelationManager::class,
            CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view'   => Pages\ViewStudent::route('/{record}'),
            'edit'   => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}