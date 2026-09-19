<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $recordTitleAttribute = 'student_name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static string|\UnitEnum|null $navigationGroup = 'واجهة الموقع';
    protected static ?string $navigationLabel = 'آراء الطلاب';
    protected static ?string $pluralLabel = 'آراء الطلاب';
    protected static ?string $label = 'رأي طالب';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return static::getModel()::where('is_published', false)->exists() ? 'warning' : 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3) // تقسيم الصفحة لـ 3 أعمدة
                    ->schema([
                        // القسم الأول: بيانات الطالب وتقييمه
                        Section::make('بيانات الطالب')
                            ->description('المعلومات الأساسية وصورة صاحب الرأي')
                            ->columnSpan(2)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('student_name')
                                            ->label('اسم الطالب')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('مثال: محمد أحمد')
                                            ->prefixIcon('heroicon-m-user'),

                                        Select::make('rating')
                                            ->label('التقييم')
                                            ->options([
                                                5 => '⭐⭐⭐⭐⭐ (ممتاز)',
                                                4 => '⭐⭐⭐⭐ (جيد جداً)',
                                                3 => '⭐⭐⭐ (جيد)',
                                                2 => '⭐⭐ (مقبول)',
                                                1 => '⭐ (ضعيف)',
                                            ])
                                            ->required()
                                            ->default(5)
                                            ->native(false), // عرض قائمة مودرن
                                    ]),

                                Grid::make(2)
                                    ->schema([
                                        Textarea::make('content_ar')
                                            ->label('الرأي (بالعربية)')
                                            ->required()
                                            ->rows(4)
                                            ->columnSpan(1),

                                        Textarea::make('content_en')
                                            ->label('الرأي (بالإنجليزية)')
                                            ->rows(4)
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        // القسم الثاني: الصورة والإعدادات
                        Section::make('الإعدادات والصورة')
                            ->description('إدارة حالة النشر وصورة الملف الشخصي')
                            ->columnSpan(1)
                            ->schema([
                                FileUpload::make('image')
                                    ->label('صورة الطالب')
                                    ->image()
                                    ->imageEditor()
                                    ->avatar()
                                    ->disk('public')
                                    ->directory('testimonials'),

                                Toggle::make('is_published')
                                    ->label('منشور على الموقع')
                                    ->default(true)
                                    ->helperText('إذا تم التعطيل، لن يظهر هذا الرأي للزوار')
                                    ->onColor('success')
                                    ->offColor('danger'),
                            ]),
                    ]),
            ])->columns(1); // تحديد عدد الأعمدة في النموذج
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                TextColumn::make('student_name')
                    ->label('اسم الطالب')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                TextColumn::make('rating')
                    ->label('التقييم')
                    ->sortable()
                    ->formatStateUsing(fn(int $state): string => str_repeat('⭐', $state))
                    ->color('warning'),

                TextColumn::make('content_ar')
                    ->label('المحتوى')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->content_ar)
                    ->searchable(),

                ToggleColumn::make('is_published')
                    ->label('حالة النشر'),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('حالة النشر')
                    ->trueLabel('الآراء المنشورة')
                    ->falseLabel('الآراء المخفية'),

                Tables\Filters\SelectFilter::make('rating')
                    ->label('التقييم')
                    ->options([
                        5 => '5 نجوم',
                        4 => '4 نجوم',
                        3 => '3 نجوم',
                    ]),
            ])
            ->actions([
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
