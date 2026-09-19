<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['name_ar', 'name_en', 'details_ar', 'details_en', 'icon', 'identifier', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function getNameAttribute()
    {
        return $this->localizedField('name_ar', 'name_en');
    }

    public function getDetailsAttribute()
    {
        return $this->localizedField('details_ar', 'details_en');
    }
}
