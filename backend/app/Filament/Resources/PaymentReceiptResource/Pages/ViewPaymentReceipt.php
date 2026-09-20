<?php

namespace App\Filament\Resources\PaymentReceiptResource\Pages;

use App\Filament\Resources\PaymentReceiptResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Notifications\Notification;

class ViewPaymentReceipt extends ViewRecord
{
    protected static string $resource = PaymentReceiptResource::class;

    protected ?string $heading = 'تفاصيل إيصال الدفع';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approveReceipt')
                ->label('الموافقة على الإيصال')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->action(function () {
                    $this->record->update(['status' => 'approved', 'reviewed_at' => now()]);
                    Notification::make()->title('تمت الموافقة على الإيصال')->success()->send();
                })
                ->requiresConfirmation()
                ->modalHeading('تأكيد الموافقة على الإيصال')
                ->modalSubmitActionLabel('نعم، موافقة'),

            Actions\Action::make('rejectReceipt')
                ->label('رفض الإيصال')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->action(function () {
                    $this->record->update(['status' => 'rejected', 'reviewed_at' => now()]);
                    Notification::make()->title('تم رفض الإيصال')->danger()->send();
                })
                ->requiresConfirmation()
                ->modalHeading('تأكيد رفض الإيصال'),

            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}