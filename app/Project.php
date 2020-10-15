<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',  'name',  'client_name',  'start_date',  'end_date',  'description'
    ];
    protected $primaryKey = 'id';
    protected $table = 'projects';

    public function scopeFilterByName($q, $name = null)
    {
        if (!$name) {
            return $q;
        }

        return $q->where('name', 'like', '%'.$name.'%');
    }

    public function scopeFilterByClientName($q, $clientName = null)
    {
        if (!$clientName) {
            return $q;
        }

        return $q->where('client_name', 'like', '%'.$clientName.'%');
    }

    public function scopeFilterByDescription($q, $description = null)
    {
        if (!$description) {
            return $q;
        }

        return $q->where('description', 'like', '%'.$description.'%');
    }

    public function scopeFilterByUserId($q, $userId = null)
    {
        if (!$userId) {
            return $q;
        }

        return $q->where('user_id', '=', $userId);
    }

    public function scopeCreatedAtDateBetween($q, $dates)
    {
        if ((!$dates['start_date'] || !$dates['end_date']) && $dates['start_date'] <= $dates['end_date']) {
            return $q;
        }

        return $q->where('created_at', '>=', getStartOfDate($dates['start_date']))->where('created_at', '<=', getEndOfDate($dates['end_date']));
    }
}
