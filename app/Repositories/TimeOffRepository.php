<?php
namespace App\Repositories;

use App\TimeOff;
use Illuminate\Validation\ValidationException;

class TimeOffRepository extends BaseRepository
{
    protected $timeoff;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(TimeOff $timeoff)
    {
        $this->timeoff  = $timeoff;
        parent::__construct($timeoff);
    }

    /**
     * Get all time-offs
     *
     * @return TimeOff
     */

    public function getAll()
    {
        return $this->timeoff->get();
    }

    /**
     * Count time-offs
     *
     * @return integer
     */

    public function count()
    {
        return $this->timeoff->count();
    }

    /**
     * Find time-off by Id
     *
     * @param integer $id
     * @return TimeOff
     * @throws ValidationException
     */

    public function findOrFail($id = null)
    {
        $timeoff = $this->timeoff->find($id);

        if (!$timeoff) {
            throw ValidationException::withMessages(['message' => 'Could not find time-off.']);
        }

        return $timeoff;
    }

    /**
     * Find time-off by User
     *
     * @param integer $userId
     * @return TimeOff
     * @throws ValidationException
     */

    public function findByUser($userId = null)
    {
        $timeoffs = $this->timeoff->filterByUserId($userId)->get();

        if (!$timeoffs) {
            throw ValidationException::withMessages(['message' => 'Time-offs not found.' ]);
        }

        return $timeoffs;
    }
}
