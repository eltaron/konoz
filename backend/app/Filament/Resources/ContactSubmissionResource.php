<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Models\ContactSubmission;
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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static ?string $recordTitleAttribute = 'name';

    // الالتزام بالـ Type hints المحددة من قبلك
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static string|\UnitEnum|null $navigationGroup = 'مركز التواصل والدعم';

    protected static ?string $navigationLabel = 'رسائل اتصل بنا';
    protected static ?string $pluralLabel = 'صندوق الوارد';
    protected static ?string $label = 'رسالة';
    protected static ?int $navigationSort = 2;

    // شارة ذكية تظهر عدد الرسائل غير المقروءة فقط
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_read', false)->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return static::getModel()::where('is_read', false)->exists() ? 'danger' : 'success';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('محتوى الرسالة الواردة')
                    ->description('عرض تفاصيل رسالة التواصل الواردة من الموقع')
                    ->aside() // التنسيق الجانبي المعتمد
                    ->icon('heroicon-m-envelope')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('اسم المرسل')
                                    ->disabled() // حماية البيانات
                                    ->prefixIcon('heroicon-m-user')
                                    ->columnSpanFull(),

                                TextInput::make('email')
                                    ->label('البريد الإلكتروني')
                                    ->disabled()
                                    ->prefixIcon('heroicon-m-at-symbol'),

                                TextInput::make('phone')
                                    ->label('رقم الهاتف')
                                    ->disabled()
                                    ->prefixIcon('heroicon-m-phone'),

                                TextInput::make('subject')
                                    ->label('عنوان الموضوع')
                                    ->disabled()
                                    ->columnSpanFull(),

                                Textarea::make('message')
                                    ->label('نص الرسالة')
                                    ->disabled()
                                    ->rows(8)
                                    ->columnSpanFull(), // استغلال المساحة كاملة كما طلبت

                                Toggle::make('is_read')
                                    ->label('تمت القراءة')
                                    ->onColor('success')
                                    ->columnSpanFull(),

                                Placeholder::make('created_at')
                                    ->label('تاريخ الإرسال')
                                    ->content(fn($record): string => $record?->created_at ? $record->created_at->format('Y-m-d H:i') : '-')
                                    ->columnSpanFull(),
                            ]),
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

                TextColumn::make('name')
                    ->label('المرسل')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn($record) => $record->email),

                TextColumn::make('subject')
                    ->label('الموضوع')
                    ->limit(40)
                    ->searchable(),

                // استخدام ToggleColumn لإدارة الحالة من الجدول مباشرة
                ToggleColumn::make('is_read')
                    ->label('مقروءة')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الاستلام')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('تصفية الرسائل')
                    ->trueLabel('الرسائل المقروءة')
                    ->falseLabel('الرسائل الجديدة')
                    ->placeholder('كل الرسائل'),
            ])
            ->actions([
                // استخدام ActionGroup من Filament\Actions
                ActionGroup::make([
                    Actions\ViewAction::make()
                        ->label('قراءة الرسالة'),
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
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactSubmissions::route('/'),
            'edit' => Pages\EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
