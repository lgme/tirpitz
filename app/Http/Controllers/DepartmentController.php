<?php
namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use App\Repositories\DepartmentRepository;

class DepartmentController extends Controller
{
    protected $request;
    protected $repo;
    protected $module = 'departments';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, DepartmentRepository $repo)
    {
        $this->request = $request;
        $this->repo = $repo;
    }

    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to store Department
     * @post ("/api/departments")
     * @return Response
     */
    public function store()
    {
        $user = (\Auth::check()) ? \Auth::user() : null;
        $request = $this->request->all();
        $request['user_id'] = isset($user) ? $user->id : null;

        $department = $this->repo->create($request);

        return $this->success(['message' => 'Department created.']);
    }

    /**
     * Used to get Department detail
     * @get ("/api/departments/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of Department"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show($id)
    {
        $department = $this->repo->findOrFail($id);

        return $this->success(compact('department'));
    }

    /**
     * Used to update Department
     * @patch ("/api/departments/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of Department"),
     * @Parameter("name", type="text", required="optional", description="Name of Department"),
     * @Parameter("lead", type="text", required="optional", description="Lead"),
     * @Parameter("description", type="text", required="optional", description="Description"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(DepartmentRequest $request, $id)
    {
        $department = $this->repo->findOrFail($id);

        $department = $this->repo->update($department, $this->request->all());

        return $this->success(['message' => 'Department updated.']);
    }

    /**
     * Used to delete Department
     * @delete ("/api/departments/{id}")
     * @param ({
     * @Parameter("id", type="int", required="true", description="Id of Department"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy($id)
    {
        $department = $this->repo->findOrFail($id);

        $this->repo->delete($department);

        return $this->success(['message' => 'Department deleted.']);
    }
}

