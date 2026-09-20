<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->color('info'),
            Actions\DeleteAction::make()
                ->visible(fn (\App\Models\User $record): bool => $record->id !== auth()->id())
                ->requiresConfirmation(),
        ];
    }
}
