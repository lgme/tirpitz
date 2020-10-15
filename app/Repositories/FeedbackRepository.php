<?php
namespace App\Repositories;

use App\Feedback;
use Illuminate\Validation\ValidationException;

class FeedbackRepository extends BaseRepository
{
    protected $feedback;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback  = $feedback;
        parent::__construct($feedback);
    }

    /**
     * Get all feedbacks
     *
     * @return Feedback
     */

    public function getAll()
    {
        return $this->feedback->get();
    }

    /**
     * Count feedbacks
     *
     * @return integer
     */

    public function count()
    {
        return $this->feedback->count();
    }

    /**
     * Find feedback by Id
     *
     * @param integer $id
     * @return Feedback
     * @throws ValidationException
     */

    public function findOrFail($id = null)
    {
        $feedback = $this->feedback->find($id);

        if (!$feedback) {
            throw ValidationException::withMessages(['message' => 'Could not find feedback.']);
        }

        return $feedback;
    }

    /**
     * Find feedback by User
     *
     * @param integer $userId
     * @return Feedback
     * @throws ValidationException
     */

    public function findByUser($userId = null)
    {
        $feedbacks = $this->feedback->filterByUserId($userId)->get();

        if (!$feedbacks) {
            throw ValidationException::withMessages(['message' => 'Feedbacks not found.' ]);
        }

        return $feedbacks;
    }
}
