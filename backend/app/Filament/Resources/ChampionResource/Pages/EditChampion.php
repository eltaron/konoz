<?php

namespace App\Filament\Resources\ChampionResource\Pages;

use App\Filament\Resources\ChampionResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditChampion extends EditRecord
{
    protected static string $resource = ChampionResource::class;
    protected Width|string|null $maxContentWidth = Width::Full;
}
