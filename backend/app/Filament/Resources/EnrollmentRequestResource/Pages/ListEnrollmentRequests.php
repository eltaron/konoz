<?php
namespace App\Filament\Resources\EnrollmentRequestResource\Pages;

use App\Filament\Resources\EnrollmentRequestResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListEnrollmentRequests extends ListRecords
{
    protected static string $resource = EnrollmentRequestResource::class;

    protected ?string $heading = 'طلبات الالتحاق بالدورات';

    protected ?string $subheading = 'مراجعة وقبول أو رفض طلبات تسجيل الطالبات في الدورات المتاحة';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
