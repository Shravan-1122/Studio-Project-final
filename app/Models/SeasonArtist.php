<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonArtist extends Model
{

    protected $table = 'SeasonArtist';
    protected $fillable = [
        'season_id', 'artist_id'
    ];

    public function webSeries()
    {
        return $this->belongsTo(WebSeries::class, 'web_id');
    }
}