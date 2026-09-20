<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $recordTitleAttribute = 'title_ar';

    // القواعد الصارمة للـ Type hints
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static string|\UnitEnum|null $navigationGroup = 'الطلاب والكادر';

    protected static ?string $navigationLabel = 'الشهادات';
    protected static ?string $pluralLabel = 'الشهادات الممنوحة';
    protected static ?string $label = 'شهادة';
    protected static ?int $navigationSort = 4;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title_ar', 'title_en', 'student.name_ar', 'course.name_ar', 'verification_code'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return static::getModel()::where('status', 'pending')->exists() ? 'warning' : 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('تفاصيل إصدار الشهادة')
                    ->description('يرجى اختيار الطالب والدورة وتحديد بيانات الشهادة بدقة')
                    ->aside()
                    ->icon('heroicon-m-identification')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // اختيار الطالب والدورة (اختياري لعرض الشهادة في معرض الموقع)
                                Select::make('student_id')
                                    ->label('الطالب المستحق')
                                    ->helperText('اختياري — يُترك فارغاً للشهادات المعروضة في معرض الموقع فقط')
                                    ->relationship('student', 'name_ar')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(), // استغلال العرض كاملاً للبحث

                                Select::make('course_id')
                                    ->label('الدورة التدريبية')
                                    ->helperText('اختياري — يُترك فارغاً للشهادات المعروضة في معرض الموقع فقط')
                                    ->relationship('course', 'name_ar')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(),

                                // عناوين الشهادة
                                TextInput::make('title_ar')
                                    ->label('عنوان الشهادة (عربي)')
                                    ->required()
                                    ->placeholder('مثال: شهادة إتمام حفظ جزء عم')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('title_en')
                                    ->label('عنوان الشهادة (English)')
                                    ->placeholder('Example: Juz Amma Completion Certificate')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                // صورة الشهادة
                                FileUpload::make('image')
                                    ->label('صورة الشهادة')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('certificates')
                                    ->columnSpanFull(),

                                // بيانات الحالة والتاريخ
                                DatePicker::make('issued_at')
                                    ->label('تاريخ الإصدار')
                                    ->default(now())
                                    ->columnSpan(1),

                                Select::make('status')
                                    ->label('حالة الشهادة')
                                    ->options([
                                        'pending' => 'قيد الإصدار (انتظار)',
                                        'delivered' => 'تم التسليم للطالب',
                                    ])
                                    ->default('pending')
                                    ->native(false)
                                    ->required()
                                    ->columnSpan(1),

                                // الظهور في معرض الشهادات بالموقع
                                \Filament\Forms\Components\Toggle::make('is_published')
                                    ->label('ظهور في معرض الشهادات')
                                    ->helperText('إذا تم التعطيل لن تظهر الشهادة في قسم صور الشهادات بالموقع')
                                    ->default(true)
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->columnSpan(1),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة')
                    ->square()
                    ->state(fn(Certificate $record): ?string => $record->image_url)
                    ->defaultImageUrl(url('/images/default-certificate.png')),

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('title_ar')
                    ->label('الشهادة')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn(Certificate $record): string => $record->title_en ?? ''),

                TextColumn::make('student.name_ar')
                    ->label('الطالب')
                    ->searchable()
                    ->color('primary'),

                TextColumn::make('course.name_ar')
                    ->label('الدورة')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('issued_at')
                    ->label('تاريخ الإصدار')
                    ->date('Y/m/d')
                    ->sortable(),

                TextColumn::make('verification_code')
                    ->label('كود التحقق')
                    ->searchable()
                    ->copyable()
                    ->copyableState(fn(string $state): string => $state)
                    ->copyMessage('تم نسخ كود التحقق')
                    ->icon('heroicon-m-key')
                    ->color('primary')
                    ->weight(FontWeight::Bold),

                \Filament\Tables\Columns\ToggleColumn::make('is_published')
                    ->label('معرض الموقع')
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'delivered' => 'success',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'قيد الإصدار',
                        'delivered' => 'تم التسليم',
                    }),
            ])
            ->defaultSort('issued_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('حالة الشهادة')
                    ->options([
                        'pending' => 'قيد الإصدار',
                        'delivered' => 'تم التسليم',
                    ]),
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('تصفية بالدورة')
                    ->relationship('course', 'name_ar'),
            ])
            ->actions([
                // استخدام ActionGroup من Filament\Actions
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()
                        ->label('تعديل الشهادة')
                        ->color('info'),
                    Actions\Action::make('open_verify')
                        ->label('مشاركة رابط التحقق')
                        ->icon('heroicon-m-link')
                        ->color('success')
                        ->openUrlInNewTab()
                        ->url(fn(Certificate $record): ?string => $record->verification_url),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('إجراءات')
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
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }
}
