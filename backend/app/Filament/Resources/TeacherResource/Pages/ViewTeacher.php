<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewTeacher extends ViewRecord
{
    protected static string $resource = TeacherResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->color('info'),
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }
}