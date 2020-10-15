<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DeviceRequest;
use App\Repositories\DeviceRepository;

class DeviceController extends Controller
{
    protected $request;
    protected $repo;
    protected $module = 'devices';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, DeviceRepository $repo)
    {
        $this->request = $request;
        $this->repo = $repo;
    }

    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to store Device
     * @post ("/api/devices")
     * @return Response
     */
    public function store()
    {
        $user = (\Auth::check()) ? \Auth::user() : null;
        $request = $this->request->all();
        $request['user_id'] = isset($user) ? $user->id : null;

        $device = $this->repo->create($request);

        return $this->success(['message' => 'Device stored.']);
    }

    /**
     * Used to get Device detail
     * @get ("/api/devices/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of Device"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show($id)
    {
        $device = $this->repo->findOrFail($id);

        return $this->success(compact('device'));
    }

    /**
     * Used to update Device
     * @patch ("/api/devices/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of Device"),
     * @Parameter("name", type="text", required="optional", description="Name of Device"),
     * @Parameter("serial_number", type="text", required="optional", description="Serial number of Device"),
     * @Parameter("status", type="text", required="optional", description="Status of Device"),
     * @Parameter("description", type="text", required="optional", description="Description"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(DeviceRequest $request, $id)
    {
        $device = $this->repo->findOrFail($id);

        $device = $this->repo->update($device, $this->request->all());

        return $this->success(['message' => 'Device updated.']);
    }

    /**
     * Used to delete Device
     * @delete ("/api/devices/{id}")
     * @param ({
     * @Parameter("id", type="int", required="true", description="Id of Device"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy($id)
    {
        $device = $this->repo->findOrFail($id);

        $this->repo->delete($device);

        return $this->success(['message' => 'Device deleted.']);
    }
}

