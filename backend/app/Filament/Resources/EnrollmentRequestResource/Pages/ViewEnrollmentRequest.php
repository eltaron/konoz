<?php
namespace App\Filament\Resources\EnrollmentRequestResource\Pages;

use App\Filament\Resources\EnrollmentRequestResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Notifications\Notification;
use App\Models\EnrollmentRequest;

class ViewEnrollmentRequest extends ViewRecord
{
    protected static string $resource = EnrollmentRequestResource::class;

    protected ?string $heading = 'تفاصيل طلب الالتحاق';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('قبول الطالبة')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn(): bool => $this->record->status === 'pending')
                ->action(function () {
                    $record = $this->record;
                    $record->update(['status' => 'approved', 'approved_at' => now()]);
                    $record->student->courses()->syncWithoutDetaching([
                        $record->course_id => ['enrolled_at' => now()->toDateString()]
                    ]);
                    Notification::make()
                        ->title('تم قبول الطلب بنجاح')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('تأكيد قبول الطالبة')
                ->modalSubmitActionLabel('نعم، قبول'),

            Actions\Action::make('reject')
                ->label('رفض الطلب')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn(): bool => $this->record->status === 'pending')
                ->form([
                    \Filament\Forms\Components\Textarea::make('notes')
                        ->label('سبب الرفض')
                        ->placeholder('اكتب سبب الرفض هنا...')
                        ->rows(4)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => 'rejected',
                        'notes' => $data['notes'],
                    ]);
                    Notification::make()
                        ->title('تم رفض الطلب')
                        ->body('تم تسجيل سبب الرفض')
                        ->danger()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('تأكيد رفض الطلب')
                ->modalDescription('يرجى كتابة سبب الرفض ليتم إبلاغ الطالبة به.'),
        ];
    }
}
