<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'message', 'status', 'admin_reply', 'replied_by', 'replied_at'];

    protected $casts = ['replied_at' => 'datetime'];

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
