<?php
namespace App\Http\Controllers;

use App\Repositories\ProjectRepository;
use App\Repositories\TimeOffRepository;
use Illuminate\Http\Request;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\DeviceRequest;
use App\Repositories\UserRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\FeedbackRepository;

class UserController extends Controller
{
    protected $request;
    protected $repo;
    protected $device;
    protected $project;
    protected $timeoff;
    protected $feedback;
    protected $module = 'user';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, UserRepository $repo, DeviceRepository $device, ProjectRepository $project, TimeOffRepository $timeoff, FeedbackRepository $feedback)
    {
        $this->request = $request;
        $this->repo = $repo;
        $this->device = $device;
        $this->project = $project;
        $this->timeoff = $timeoff;
        $this->feedback = $feedback;
    }

    /**
     * Used to get all Users
     * @get ("/api/users")
     * @return Response
     */
    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to get User detail
     * @get ("/api/users/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of User"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show($id)
    {
        $user = $this->repo->findOrFail($id);

        return $this->success(compact('user'));
    }

    /**
     * Used to update User
     * @patch ("/api/users/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of User"),
     * @Parameter("first_name", type="text", required="true", description="First Name of User"),
     * @Parameter("last_name", type="text", required="true", description="Last Name of User"),
     * @Parameter("image_url", type="text", required="optional", description="Profile Image URL"),
     * @Parameter("phone", type="text", required="optional", description="Phone Number"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UserProfileRequest $request, $id)
    {
        $user = $this->repo->findOrFail($id);

        $user = $this->repo->update($user, $this->request->all());

        return $this->success(['message' => 'User updated.']);
    }

    /**
     * Used to delete User
     * @delete ("/api/users/{id}")
     * @param ({
     * @Parameter("id", type="int", required="true", description="Id of User"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy($id)
    {
        $user = $this->repo->findOrFail($id);

        $this->repo->delete($user);

        return $this->success(['message' => 'User deleted.']);
    }

    /**
     * Used to get User devices
     * @get ("/api/users/{id}/devices")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of User"),
     * })
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getDevices($id)
    {
        $user = $this->repo->findOrFail($id);
        $devices = $this->device->findByUser($id);

        return $this->success(compact('user', 'devices'));
    }

    /**
     * Used to get User device
     * @get ("/api/users/{user_id}/devices/{device_id}")
     * @param ({
     * @Parameter("user_id", type="integer", required="true", description="Id of User"),
     * @Parameter("device_id", type="integer", required="true", description="Id of Device"),
     * })
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getDevice($userId, $deviceId)
    {
        $user = $this->repo->findOrFail($userId);
        $device = $this->device->findOrFail($deviceId);

        return $this->success(compact('user', 'device'));
    }

    /**
     * Used to create a Device for User
     * @post ("/api/users/{id}/devices")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of User"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addDevice(DeviceRequest $request, $id)
    {
        $user = $this->repo->findOrFail($id);
        $new_device = $this->device->create($this->request->all());
        $new_device->user_id = $id;
        $new_device->save();

        return response()->json(['status' => 'success']);
    }

    /**
     * Used to get User projects
     * @get ("/api/users/{id}/projects")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of User"),
     * })
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getProjects($id)
    {
        $user = $this->repo->findOrFail($id);
        $projects = $this->project->findByUser($id);

        return $this->success(compact('user', 'projects'));
    }

    /**
     * Used to get User project
     * @get ("/api/users/{user_id}/projects/{project_id}")
     * @param ({
     * @Parameter("user_id", type="integer", required="true", description="Id of User"),
     * @Parameter("project_id", type="integer", required="true", description="Id of Project"),
     * })
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getProject($userId, $projectId)
    {
        $user = $this->repo->findOrFail($userId);
        $project = $this->project->findOrFail($projectId);

        return $this->success(compact('user', 'project'));
    }

    /**
     * Used to get User timeoffs
     * @get ("/api/users/{id}/time-off")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of User"),
     * })
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getTimeoffs($id)
    {
        $user = $this->repo->findOrFail($id);
        $timeoffs = $this->timeoff->findByUser($id);

        return $this->success(compact('user', 'timeoffs'));
    }

    /**
     * Used to get User feedbacks
     * @get ("/api/users/{id}/feedback")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of User"),
     * })
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getFeedbacks($id)
    {
        $user = $this->repo->findOrFail($id);
        $feedbacks = $this->feedback->findByUser($id);

        return $this->success(compact('user', 'feedbacks'));
    }
}

