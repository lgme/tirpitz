<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'user_id', 'name', 'serial_number', 'status', 'type', 'description'
    ];
    protected $primaryKey = 'id';
    protected $table = 'devices';

    public function scopeFilterByName($q, $name = null)
    {
        if (!$name) {
            return $q;
        }

        return $q->where('name', 'like', '%'.$name.'%');
    }

    public function scopeFilterBySerialNumber($q, $serialNumber = null)
    {
        if (!$serialNumber) {
            return $q;
        }

        return $q->where('serial_number', '=', $serialNumber);
    }

    public function scopeFilterByStatus($q, $status = null)
    {
        if (!$status) {
            return $q;
        }

        return $q->where('status', '=', $status);
    }

    public function scopeFilterByType($q, $type = null)
    {
        if (!$type) {
            return $q;
        }

        return $q->where('type', '=', $type);
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
