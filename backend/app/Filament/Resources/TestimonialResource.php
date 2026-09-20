<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $recordTitleAttribute = 'student_name';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static string|\UnitEnum|null $navigationGroup = 'واجهة الموقع';
    protected static ?string $navigationLabel = 'آراء الطلاب';
    protected static ?string $pluralLabel = 'آراء الطلاب';
    protected static ?string $label = 'رأي طالب';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::where('is_published', false)->exists() ? 'warning' : 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1) // الـ schema كله عمود واحد ياخد العرض كامل
            ->schema([
                Grid::make([
                    'default' => 1,
                    'lg'      => 3,
                ])
                    ->columnSpanFull()
                    ->schema([
                        // القسم الأول: بيانات الطالب وتقييمه
                        Section::make('بيانات الطالب')
                            ->description('المعلومات الأساسية وصورة صاحب الرأي')
                            ->columnSpan([
                                'default' => 1,
                                'lg'      => 2,
                            ])
                            ->schema([
                                Grid::make(2)
                                    ->columnSpanFull()
                                    ->schema([
                                        TextInput::make('student_name')
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
                                            ->native(false),
                                    ]),

                                Grid::make(2)
                                    ->columnSpanFull()
                                    ->schema([
                                        Textarea::make('content_ar')
                                            ->label('الرأي (بالعربية)')
                                            ->required()
                                            ->rows(4),

                                        Textarea::make('content_en')
                                            ->label('الرأي (بالإنجليزية)')
                                            ->rows(4),
                                    ]),
                            ]),

                        // القسم الثاني: الصورة والإعدادات
                        Section::make('الإعدادات والصورة')
                            ->description('إدارة حالة النشر وصورة الملف الشخصي')
                            ->columnSpan([
                                'default' => 1,
                                'lg'      => 1,
                            ])
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
            ]);
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
                TernaryFilter::make('is_published')
                    ->label('حالة النشر')
                    ->trueLabel('الآراء المنشورة')
                    ->falseLabel('الآراء المخفية'),

                SelectFilter::make('rating')
                    ->label('التقييم')
                    ->options([
                        5 => '5 نجوم',
                        4 => '4 نجوم',
                        3 => '3 نجوم',
                        2 => 'نجمتان',
                        1 => 'نجمة',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Actions\EditAction::make()->color('info'),
                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات'),
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
            'index'  => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit'   => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
