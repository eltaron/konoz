<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait HasLocalizedFields
{
    protected function localizedField(string $arField, string $enField): string
    {
        $locale = App::getLocale();
        $enValue = $this->{$enField};
        $arValue = $this->{$arField};
        return $locale === 'en' ? ($enValue ?: ($arValue ?? '')) : ($arValue ?? '');
    }
}
