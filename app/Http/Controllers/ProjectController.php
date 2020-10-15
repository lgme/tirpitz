<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    protected $request;
    protected $repo;
    protected $module = 'projects';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, ProjectRepository $repo)
    {
        $this->request = $request;
        $this->repo = $repo;
    }

    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to store Project
     * @post ("/api/projects")
     * @return Response
     */
    public function store()
    {
        $user = (\Auth::check()) ? \Auth::user() : null;
        $request = $this->request->all();
        $request['user_id'] = isset($user) ? $user->id : null;

        $project = $this->repo->create($request);

        return $this->success(['message' => 'Project created.']);
    }

    /**
     * Used to get Project detail
     * @get ("/api/projects/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of Project"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show($id)
    {
        $project = $this->repo->findOrFail($id);

        return $this->success(compact('project'));
    }

    /**
     * Used to update Project
     * @patch ("/api/projects/{id}")
     * @param ({
     * @Parameter("id", type="integer", required="true", description="Id of Project"),
     * @Parameter("name", type="text", required="optional", description="Name of Project"),
     * @Parameter("client_name", type="text", required="optional", description="Client name of Project"),
     * @Parameter("start_date", type="text", required="optional", description="Start date of Project"),
     * @Parameter("end_date", type="text", required="optional", description="End date of Project"),
     * @Parameter("description", type="text", required="optional", description="Description"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(ProjectRequest $request, $id)
    {
        $project = $this->repo->findOrFail($id);

        $project = $this->repo->update($project, $this->request->all());

        return $this->success(['message' => 'Project updated.']);
    }

    /**
     * Used to delete Project
     * @delete ("/api/projects/{id}")
     * @param ({
     * @Parameter("id", type="int", required="true", description="Id of Project"),
     * })
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy($id)
    {
        $project = $this->repo->findOrFail($id);

        $this->repo->delete($project);

        return $this->success(['message' => 'Project deleted.']);
    }
}

