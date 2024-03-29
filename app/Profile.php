<?php
namespace App;

use Eloquent;

class Profile extends Eloquent
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'image_url', 'phone'
    ];
    protected $primaryKey = 'id';
    protected $table = 'profiles';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
