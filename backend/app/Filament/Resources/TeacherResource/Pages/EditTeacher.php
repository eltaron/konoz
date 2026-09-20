<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;
    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->color('info'),
            Actions\DeleteAction::make()->requiresConfirmation(),
        ];
    }
}
