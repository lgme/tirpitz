<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeOff extends Model

{
    protected $fillable = [
        'user_id', 'status', 'request_type', 'start_date', 'end_date'
    ];
    protected $primaryKey = 'id';
    protected $table = 'timeoffs';

    public function scopeFilterByUserId($q, $userId = null)
    {
        if (!$userId) {
            return $q;
        }

        return $q->where('user_id', '=', $userId);
    }
}
