<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    use \App\Traits\HasLocalizedFields;

    protected $fillable = ['name_ar', 'name_en', 'achievement_ar', 'achievement_en', 'image', 'icon', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function getNameAttribute()
    {
        return $this->localizedField('name_ar', 'name_en');
    }

    public function getAchievementAttribute()
    {
        return $this->localizedField('achievement_ar', 'achievement_en');
    }
}
