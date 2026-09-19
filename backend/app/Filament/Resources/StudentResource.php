<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\StudentResource\RelationManagers\AttendanceRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\JuzProgressRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\CertificatesRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\CoursesRelationManager;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $recordTitleAttribute = 'name_ar';

    // القواعد الصارمة للـ Type hints
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|\UnitEnum|null $navigationGroup = 'شؤون الطلاب';

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
                Section::make('الملف الشخصي للطالب')
                    ->description('إدارة البيانات الشخصية والأكاديمية للطالب')
                    ->aside()
                    ->schema([
                        Tabs::make('Student Data')
                            ->tabs([
                                // التبويب الأول: الهوية الشخصية
                                TabsTab::make('البيانات الأساسية')
                                    ->icon('heroicon-m-user')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('name_ar')
                                                ->label('الاسم الكامل (عربي)')
                                                ->required()
                                                ->maxLength(255)
                                                ->columnSpanFull(), // شغل المساحة كاملة

                                            TextInput::make('name_en')
                                                ->label('الاسم الكامل (English)')
                                                ->maxLength(255)
                                                ->columnSpanFull(),

                                            Select::make('gender')
                                                ->label('الجنس')
                                                ->options([
                                                    'male' => 'ذكر',
                                                    'female' => 'أنثى',
                                                ])->native(false),

                                            TextInput::make('age')
                                                ->label('العمر')
                                                ->numeric()
                                                ->prefixIcon('heroicon-m-calendar'),
                                        ]),
                                    ]),

                                // التبويب الثاني: التواصل والربط التقني
                                TabsTab::make('معلومات التواصل')
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
                                                ->label('إنشاء حساب مستخدم')
                                                ->default(false)
                                                ->live()
                                                ->helperText('فعّل لإنشاء حساب تسجيل دخول للطالب')
                                                ->columnSpanFull(),

                                            TextInput::make('user_email')
                                                ->label('البريد الإلكتروني للحساب')
                                                ->email()
                                                ->visible(fn($get) => $get('create_user'))
                                                ->required(fn($get) => $get('create_user'))
                                                ->prefixIcon('heroicon-m-at-symbol')
                                                ->columnSpanFull(),

                                            TextInput::make('user_password')
                                                ->label('كلمة المرور')
                                                ->password()
                                                ->visible(fn($get) => $get('create_user'))
                                                ->required(fn($get) => $get('create_user'))
                                                ->prefixIcon('heroicon-m-lock-closed')
                                                ->columnSpanFull(),

                                            Select::make('user_id')
                                                ->label('أو ربط بحساب مستخدم موجود')
                                                ->relationship('user', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->helperText('اربط الطالب بحساب موجود مسبقاً')
                                                ->columnSpanFull(),
                                        ]),
                                    ]),

                                // التبويب الثالث: الحالة الدراسية
                                TabsTab::make('الوضع الأكاديمي')
                                    ->icon('heroicon-m-academic-cap')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('level')
                                                ->label('المستوى الحالي')
                                                ->placeholder('مثال: المستوى الثالث'),

                                            Select::make('status')
                                                ->label('حالة الطالب')
                                                ->options([
                                                    'active' => 'نشط',
                                                    'suspended' => 'معلق',
                                                    'inactive' => 'غير نشط',
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
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('name_ar')
                    ->label('اسم الطالب')
                    ->searchable(['name_ar', 'name_en'])
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(Student $record): string => $record->name_en ?? ''),

                TextColumn::make('phone')
                    ->label('الجوال')
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        'inactive' => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'نشط',
                        'suspended' => 'معلق',
                        'inactive' => 'غير نشط',
                    }),

                TextColumn::make('level')
                    ->label('المستوى')
                    ->toggleable(),

                TextColumn::make('joined_at')
                    ->label('تاريخ الانضمام')
                    ->date('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('تصفية بالحالة')
                    ->options([
                        'active' => 'نشط',
                        'suspended' => 'معلق',
                    ]),
                Tables\Filters\SelectFilter::make('gender')
                    ->label('الجنس')
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()->color('info'),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات الطالب')
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
        return parent::getEloquentQuery()->where('gender', 'female');
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
