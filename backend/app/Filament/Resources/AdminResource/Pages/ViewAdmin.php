<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Width;

class ViewAdmin extends ViewRecord
{
    protected static string $resource = AdminResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->color('info'),
            Actions\DeleteAction::make()
                ->visible(fn (\App\Models\User $record): bool => $record->id !== auth()->id())
                ->requiresConfirmation(),
        ];
    }
}