<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'user_id', 'sender', 'target', 'title', 'type', 'text'
    ];
    protected $primaryKey = 'id';
    protected $table = 'feedbacks';

    public function scopeFilterByUserId($q, $userId = null)
    {
        if (!$userId) {
            return $q;
        }

        return $q->where('user_id', '=', $userId);
    }
}
