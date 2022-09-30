<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'send_by_tourist',
        'unread',
        'tourist_id',
        'hotel_id',
    ];

    public function hotel()
    {
        return $this->belongsTo(HotelProfile::class,'hotel_id');
    }

    public function tourist()
    {
        return $this->belongsTo(Tourist::class,'tourist_id');
    }

}
