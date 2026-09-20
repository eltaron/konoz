<?php

namespace App\Filament\Resources\ChampionResource\Pages;

use App\Filament\Resources\ChampionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateChampion extends CreateRecord
{
    protected static string $resource = ChampionResource::class;
    protected Width|string|null $maxContentWidth = Width::Full;
}
