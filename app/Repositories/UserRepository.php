<?php
namespace App\Repositories;

use App\Profile;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserRepository extends BaseRepository
{
    protected $user;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user  = $user;
        parent::__construct($this->user->with('profile', 'department'));
    }

    /**
     * Get all users with profile
     *
     * @return User
     */

    public function getAllByProfile()
    {
        return $this->user->with('profile')->get();
    }

    /**
     * Get all users from departments
     *
     * @return User
     */

    public function getAllByDepartment()
    {
        return $this->user->with('department')->get();
    }

    /**
     * Count users
     *
     * @return integer
     */

    public function count()
    {
        return $this->user->count();
    }

    /**
     * Count users registered between dates
     *
     * @return integer
     */

    public function countDateBetween($start_date, $end_date)
    {
        return $this->user->createdAtDateBetween(['start_date' => $start_date, 'end_date' => $end_date])->count();
    }

    /**
     * Find user by Id
     *
     * @param integer $id
     * @return User
     * @throws ValidationException
     */

    public function findOrFail($id = null)
    {
        $user = $this->user->with('profile', 'department')->find($id);

        if (!$user) {
            throw ValidationException::withMessages(['message' => 'Could not find user.']);
        }

        return $user;
    }

    /**
     * Find user by Email
     *
     * @param email $email
     * @return User
     */

    public function findByEmail($email = null)
    {
        return $this->user->with('profile', 'department')->filterByEmail($email)->first();
    }

    /**
     * Find user by activation token
     *
     * @param string $token
     * @return User
     */

    public function findByActivationToken($token = null)
    {
        return $this->user->with('profile', 'department')->whereActivationToken($token)->first();
    }

    /**
     * List user except authenticated user by name & email
     *
     * @param string $token
     * @return User
     */

    public function listByNameAndEmailExceptAuthUser()
    {
        return $this->user->where('id', '!=', \Auth::user()->id)->get()->pluck('name_with_email', 'id')->all();
    }

    /**
     * Paginate all todos using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    public function paginate($params = array())
    {
        $sort_by    = isset($params['sort_by']) ? $params['sort_by'] : 'created_at';
        $order      = isset($params['order']) ? $params['order'] : 'desc';
        $page_length = isset($params['page_length']) ? $params['page_length'] : config('config.page_length');

        $query = $this->query($params);

        if ($sort_by === 'first_name') {
            $query->select('users.*', \DB::raw('(select first_name from profiles where users.id = profiles.user_id) as first_name'))->orderBy('first_name', $order);
        } elseif ($sort_by === 'last_name') {
            $query->select('users.*', \DB::raw('(select last_name from profiles where users.id = profiles.user_id) as last_name'))->orderBy('last_name', $order);
        } else {
            $query->orderBy($sort_by, $order);
        }

        return $query->paginate($page_length);
    }

    /**
     * Create a new user.
     *
     * @param array $params
     * @return User
     */

    public function create($params)
    {
        $user = $this->user->forceCreate($this->formatParams($params, 'register'));

        $profile = $this->associateProfile($user);

        $this->updateProfile($profile, $params);

        return $user;
    }

    /**
     * Update given user.
     *
     * @param User $user
     * @param array $params
     *
     * @return User
     */
    public function update(User $user, $params = array())
    {
        $this->updateProfile($user->Profile, $params);

        return $user;
    }

    /**
     * Delete user.
     *
     * @param User $user
     * @return bool|null
     * @throws \Exception
     */
    public function delete(User $user)
    {
        return $user->delete();
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */

    private function formatParams($params, $action = 'create')
    {
        $formatted = [
            'email'             => isset($params['email']) ? $params['email'] : null,
            'status'            => 'activated',
            'password'          => isset($params['password']) ? bcrypt($params['password']) : null,
            'activation_token'  => Str::uuid()
        ];

        if ($action === 'register') {
            if (config('config.email_verification')) {
                $formatted['status'] = 'pending_activation';
            } elseif (config('config.account_approval')) {
                $formatted['status'] = 'pending_approval';
            }
        }

        return $formatted;
    }

    /**
     * Associate user to profile.
     *
     * @param User
     * @return Profile
     */

    private function associateProfile($user)
    {
        $profile = new Profile;
        $user->profile()->save($profile);

        return $profile;
    }

    /**
     * Update user profile.
     *
     * @param Profile
     * @param array $params
     * @return null
     */

    private function updateProfile($profile, $params = array())
    {
        $profile->first_name = isset($params['first_name']) ? $params['first_name'] : $profile->first_name;
        $profile->last_name  = isset($params['last_name']) ? $params['last_name'] : $profile->last_name;
        $profile->image_url  = isset($params['image_url']) ? $params['image_url'] : $profile->image_url;
        $profile->phone      = isset($params['phone']) ? $params['phone'] : $profile->phone;
        $profile->save();
    }

    /**
     * Update given user status.
     *
     * @param User $user
     * @param string $status
     *
     * @return User
     */
    private function status(User $user, $status = null)
    { }

    /**
     * Force reset user password.
     *
     * @param User $user
     * @param string $password
     *
     * @return User
     */
    private function forceResetPassword(User $user, $password = null)
    { }

    /**
     * Send email to user.
     *
     * @param User $user
     * @param array $params
     *
     * @return null
     */
    private function sendEmail(User $user, $params = array())
    { }

    /**
     * Delete multiple users.
     *
     * @param array $ids
     * @return bool|null
     */
    private function deleteMultiple($ids)
    { }
}
