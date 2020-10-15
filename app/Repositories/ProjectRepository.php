<?php
namespace App\Repositories;

use App\Project;
use Illuminate\Validation\ValidationException;

class ProjectRepository extends BaseRepository
{
    protected $project;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project  = $project;
        parent::__construct($project);
    }

    /**
     * Get all projects
     *
     * @return Project
     */

    public function getAll()
    {
        return $this->project->get();
    }

    /**
     * Count projects
     *
     * @return integer
     */

    public function count()
    {
        return $this->project->count();
    }

    /**
     * Find project by Id
     *
     * @param integer $id
     * @return Project
     * @throws ValidationException
     */

    public function findOrFail($id = null)
    {
        $project = $this->project->find($id);

        if (!$project) {
            throw ValidationException::withMessages(['message' => 'Could not find project.']);
        }

        return $project;
    }

    /**
     * Find project by User
     *
     * @param integer $userId
     * @return Project
     * @throws ValidationException
     */

    public function findByUser($userId = null)
    {
        $projects = $this->project->filterByUserId($userId)->get();

        if (!$projects) {
            throw ValidationException::withMessages(['message' => 'Projects not found.' ]);
        }

        return $projects;
    }

    /**
     * Create a new project.
     *
     * @param array $params
     * @return Project
     */

    public function create($params)
    {
        $project = $this->project->forceCreate($this->formatParams($params));

        return $project;
    }

    /**
     * Update given project.
     *
     * @param Project $project
     * @param array $params
     *
     * @return Project
     */
    public function update(Project $project, $params = array())
    {
        $project->name           = isset($params['name']) ? $params['name'] : $project->name;
        $project->client_name    = isset($params['client_name']) ? $params['client_name'] : $project->client_name;
        $project->start_date     = isset($params['start_date']) ? $params['start_date'] : $project->start_date;
        $project->end_date       = isset($params['end_date']) ? $params['end_date'] : $project->end_date;
        $project->description    = isset($params['description']) ? $params['description'] : $project->description;
        $project->save();

        return $project;
    }

    /**
     * Delete project.
     *
     * @param Project $project
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Project $project)
    {
        return $project->delete();
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */

    private function formatParams($params)
    {
        $formatted = [
            'name'              => isset($params['name']) ? $params['name'] : null,
            'user_id'           => isset($params['user_id']) ? $params['user_id'] : null,
            'client_name'       => isset($params['client_name']) ? $params['client_name'] : null,
            'start_date'        => isset($params['start_date']) ? $params['start_date'] : null,
            'end_date'          => isset($params['end_date']) ? $params['end_date'] : null,
            'description'       => isset($params['description']) ? $params['description'] : null
        ];

        return $formatted;
    }
}
