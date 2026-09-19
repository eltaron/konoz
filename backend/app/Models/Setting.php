<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['key', 'value_ar', 'value_en'];

    public function getValueAttribute()
    {
        return $this->localizedField('value_ar', 'value_en');
    }
}
