<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentReceiptResource\Pages;
use App\Models\PaymentReceipt;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class PaymentReceiptResource extends Resource
{
    protected static ?string $model = PaymentReceipt::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';
    protected static string|\UnitEnum|null $navigationGroup = 'الطلاب والكادر';

    protected static ?string $navigationLabel = 'إيصالات الدفع';
    protected static ?string $pluralLabel = 'إيصالات الدفع';
    protected static ?string $label = 'إيصال دفع';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return static::getModel()::where('status', 'pending')->exists() ? 'warning' : 'success';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('مراجعة إيصال الدفع')
                    ->description('تفاصيل الطالبة والدورة وطريقة التحويل')
                    ->aside()
                    ->icon('heroicon-m-banknotes')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Placeholder::make('receipt.preview')
                                    ->label('صورة الإيصال (اضغط للتكبير)')
                                    ->content(fn (?PaymentReceipt $record): string => $record?->receipt_url
                                        ? '<a href="' . e($record->receipt_url) . '" target="_blank" rel="noopener"><img src="' . e($record->receipt_url) . '" style="max-width:100%;max-height:220px;border-radius:12px;border:1px solid rgba(0,0,0,0.08);" /></a>'
                                        : 'لا يوجد إيصال مرفق')
                                    ->html()
                                    ->columnSpanFull(),

                                Placeholder::make('student.name_ar')
                                    ->label('اسم الطالبة')
                                    ->content(fn (?PaymentReceipt $record): string => $record?->student?->name_ar ?? '-'),

                                Placeholder::make('student.phone')
                                    ->label('رقم الجوال')
                                    ->content(fn (?PaymentReceipt $record): string => $record?->student?->phone ?? '-'),

                                Placeholder::make('course.name_ar')
                                    ->label('الدورة')
                                    ->content(fn (?PaymentReceipt $record): string => $record?->course?->name_ar ?? '-'),

                                Placeholder::make('paymentMethod.name')
                                    ->label('طريقة التحويل')
                                    ->content(fn (?PaymentReceipt $record): string => $record?->paymentMethod?->name ?? '-'),

                                Placeholder::make('amount')
                                    ->label('المبلغ')
                                    ->content(fn (?PaymentReceipt $record): string => $record ? number_format((float) $record->amount, 2) : '-'),

                                Placeholder::make('status')
                                    ->label('حالة الإيصال')
                                    ->content(fn (?PaymentReceipt $record): string => $record?->status_label ?? '-'),

                                Placeholder::make('note')
                                    ->label('ملاحظات الطالبة')
                                    ->content(fn (?PaymentReceipt $record): string => $record?->note ?? '—')
                                    ->columnSpanFull(),

                                Placeholder::make('reviewed_at')
                                    ->label('تاريخ المراجعة')
                                    ->content(fn (?PaymentReceipt $record): string => $record?->reviewed_at ? $record->reviewed_at->format('Y-m-d H:i') : '—'),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('receipt_path')
                    ->label('الإيصال')
                    ->square()
                    ->extraImgAttributes(['style' => 'border-radius:10px;'])
                    ->state(fn (PaymentReceipt $record): ?string => $record->receipt_url)
                    ->tooltip('عرض إيصال التحويل'),

                Tables\Columns\TextColumn::make('student.name_ar')
                    ->label('الطالبة')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn (PaymentReceipt $record): string => $record->student?->phone ?? ''),

                Tables\Columns\TextColumn::make('course.name_ar')
                    ->label('الدورة')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->label('طريقة التحويل')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('المبلغ')
                    ->numeric(2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'قيد المراجعة',
                        'approved' => 'تمت الموافقة',
                        'rejected' => 'مرفوض',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الرفع')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('تصفية بالحالة')
                    ->options([
                        'pending' => 'قيد المراجعة',
                        'approved' => 'تمت الموافقة',
                        'rejected' => 'مرفوض',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Actions\ViewAction::make(),

                    Actions\Action::make('approveReceipt')
                        ->label('الموافقة على الإيصال')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->visible(fn (PaymentReceipt $record): bool => $record->status === 'pending')
                        ->action(function (PaymentReceipt $record) {
                            $record->update(['status' => 'approved', 'reviewed_at' => now()]);
                            Notification::make()
                                ->title('تمت الموافقة على الإيصال')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('تأكيد الموافقة على الإيصال')
                        ->modalSubmitActionLabel('نعم، موافقة'),

                    Actions\Action::make('rejectReceipt')
                        ->label('رفض الإيصال')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle')
                        ->visible(fn (PaymentReceipt $record): bool => $record->status === 'pending')
                        ->action(function (PaymentReceipt $record) {
                            $record->update(['status' => 'rejected', 'reviewed_at' => now()]);
                            Notification::make()
                                ->title('تم رفض الإيصال')
                                ->danger()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('تأكيد رفض الإيصال'),

                    Actions\DeleteAction::make(),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('خيارات الإيصال'),
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
            'index' => Pages\ListPaymentReceipts::route('/'),
            'view' => Pages\ViewPaymentReceipt::route('/{record}'),
            'edit' => Pages\EditPaymentReceipt::route('/{record}/edit'),
        ];
    }
}