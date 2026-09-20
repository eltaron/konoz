<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages;
use App\Models\SupportTicket;
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
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static ?string $recordTitleAttribute = 'subject';

    // الالتزام بالـ Type hints المحددة
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-lifebuoy';
    protected static string|\UnitEnum|null $navigationGroup = 'الدعم والتواصل';

    protected static ?string $navigationLabel = 'تذاكر الدعم';
    protected static ?string $pluralLabel = 'تذاكر الدعم الفني';
    protected static ?string $label = 'تذكرة';
    protected static ?int $navigationSort = 1;

    // شارة ذكية تعرض عدد التذاكر المفتوحة فقط للتنبيه
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'open')->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return static::getModel()::where('status', 'open')->exists() ? 'danger' : 'success';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(3)
                    ->schema([
                        // القسم الأول: تفاصيل التذكرة (بيانات العميل)
                        Section::make('تفاصيل التذكرة')
                            ->description('المعلومات المرسلة من قبل المستخدم')
                            ->columnSpan(2)
                            ->aside()
                            ->icon('heroicon-m-envelope-open')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('اسم المرسل')
                                            ->disabled() // حماية البيانات الأصلية
                                            ->prefixIcon('heroicon-m-user'),

                                        TextInput::make('email')
                                            ->label('البريد الإلكتروني')
                                            ->email()
                                            ->disabled()
                                            ->prefixIcon('heroicon-m-at-symbol'),

                                        TextInput::make('subject')
                                            ->label('موضوع التذكرة')
                                            ->disabled()
                                            ->columnSpanFull(),

                                        Textarea::make('message')
                                            ->label('نص الرسالة')
                                            ->disabled()
                                            ->rows(5)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // القسم الثاني: إجراءات الإدارة (الرد والحالة)
                        Section::make('إدارة التذكرة')
                            ->description('اتخاذ إجراء والرد على المستخدم')
                            ->columnSpan(1)
                            ->schema([
                                Select::make('status')
                                    ->label('حالة التذكرة')
                                    ->options([
                                        'open' => 'مفتوحة (قيد الانتظار)',
                                        'replied' => 'تم الرد',
                                        'closed' => 'مغلقة (تم الحل)',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->selectablePlaceholder(false),

                                Textarea::make('admin_reply')
                                    ->label('رد الإدارة')
                                    ->placeholder('اكتب ردك هنا ليرسل للمستخدم...')
                                    ->rows(6)
                                    ->required(fn($get) => $get('status') === 'replied'),

                                Placeholder::make('created_at')
                                    ->label('تاريخ الاستلام')
                                    ->content(fn($record): string => $record?->created_at ? $record->created_at->diffForHumans() : '-'),

                                Placeholder::make('replied_at')
                                    ->label('آخر رد في')
                                    ->content(fn($record): string => $record?->replied_at ? $record->replied_at->format('Y-m-d H:i') : 'لم يتم الرد بعد'),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('رقم التذكرة')
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
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'open' => 'danger',
                        'replied' => 'success',
                        'closed' => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'open' => 'مفتوحة',
                        'replied' => 'تم الرد',
                        'closed' => 'مغلقة',
                    }),

                TextColumn::make('created_at')
                    ->label('تاريخ الإرسال')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),

                TextColumn::make('replied_at')
                    ->label('تاريخ الرد')
                    ->dateTime('Y/m/d H:i')
                    ->placeholder('لا يوجد رد')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('تصفية حسب الحالة')
                    ->options([
                        'open' => 'مفتوحة',
                        'replied' => 'تم الرد',
                        'closed' => 'مغلقة',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Actions\ViewAction::make(),
                    Actions\EditAction::make()
                        ->label('رد على التذكرة')
                        ->color('info')
                        ->icon('heroicon-m-chat-bubble-left-right'),
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
            ->striped()
            ->poll('30s'); // تحديث الجدول تلقائياً كل 30 ثانية لمتابعة التذاكر الجديدة
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'edit' => Pages\EditSupportTicket::route('/{record}/edit'),
        ];
    }
}
