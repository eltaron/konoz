<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['slug', 'name_ar', 'name_en', 'icon'];

    public function getNameAttribute()
    {
        return $this->localizedField('name_ar', 'name_en');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
