<?php
namespace App\Repositories;

use App\Department;
use Illuminate\Validation\ValidationException;

class DepartmentRepository extends BaseRepository
{
    protected $department;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(Department $department)
    {
        $this->department  = $department;
        parent::__construct($department);
    }

    /**
     * Get all departments
     *
     * @return Department
     */

    public function getAll()
    {
        return $this->department->get();
    }

    /**
     * Count departments
     *
     * @return integer
     */

    public function count()
    {
        return $this->department->count();
    }

    /**
     * Find department by Id
     *
     * @param integer $id
     * @return Department
     * @throws ValidationException
     */

    public function findOrFail($id = null)
    {
        $department = $this->department->find($id);

        if (!$department) {
            throw ValidationException::withMessages(['message' => 'Could not find department.']);
        }

        return $department;
    }

    /**
     * Create a new department.
     *
     * @param array $params
     * @return Department
     */

    public function create($params)
    {
        $department = $this->department->forceCreate($this->formatParams($params));

        return $department;
    }

    /**
     * Update given department.
     *
     * @param Department $department
     * @param array $params
     *
     * @return Department
     */
    public function update(Department $department, $params = array())
    {
        $department->name           = isset($params['name']) ? $params['name'] : $department->name;
        $department->lead           = isset($params['lead']) ? $params['lead'] : $department->lead;
        $department->description    = isset($params['description']) ? $params['description'] : $department->description;
        $department->save();

        return $department;
    }

    /**
     * Delete department.
     *
     * @param Department $department
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Department $department)
    {
        return $department->delete();
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
            'lead'              => isset($params['lead']) ? $params['lead'] : null,
            'description'       => isset($params['description']) ? $params['description'] : null
        ];

        return $formatted;
    }
}
