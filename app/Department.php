<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'user_id', 'name', 'lead', 'description'
    ];
    protected $primaryKey = 'id';
    protected $table = 'departments';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeFilterByName($q, $name = null)
    {
        if (!$name) {
            return $q;
        }

        return $q->where('name', 'like', '%'.$name.'%');
    }

    public function scopeFilterByLead($q, $lead = null)
    {
        if (!$lead) {
            return $q;
        }

        return $q->where('lead', 'like', '%'.$lead.'%');
    }

    public function scopeFilterByDescription($q, $description = null)
    {
        if (!$description) {
            return $q;
        }

        return $q->where('description', 'like', '%'.$description.'%');
    }

    public function scopeCreatedAtDateBetween($q, $dates)
    {
        if ((!$dates['start_date'] || !$dates['end_date']) && $dates['start_date'] <= $dates['end_date']) {
            return $q;
        }

        return $q->where('created_at', '>=', getStartOfDate($dates['start_date']))->where('created_at', '<=', getEndOfDate($dates['end_date']));
    }
}
