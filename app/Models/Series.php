<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $table = 'webseries'; // Assuming your table name is 'webseries'

    protected $primaryKey = 'id'; // Assuming your primary key column is 'id'

    public $incrementing = false; // Assuming your primary key is not auto-incrementing

    protected $fillable = [
        'id',
        'title',
        'description',
        'theme_id',
        'status',
        'created_by',
        'updated_by',
        'active',
    ];
    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'web_artist', 'web_id', 'artist_id');
    }

   public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
   
}