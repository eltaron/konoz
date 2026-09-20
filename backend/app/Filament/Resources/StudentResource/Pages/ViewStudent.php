<?php
namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->color('info'),
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }
}