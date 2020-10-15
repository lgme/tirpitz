<?php
namespace App\Repositories;

use App\Device;
use Illuminate\Validation\ValidationException;

class DeviceRepository extends BaseRepository
{
    protected $device;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(Device $device)
    {
        $this->device  = $device;
        parent::__construct($device);
    }

    /**
     * Get all devices
     *
     * @return Device
     */

    public function getAll()
    {
        return $this->device->get();
    }

    /**
     * Count devices
     *
     * @return integer
     */

    public function count()
    {
        return $this->device->count();
    }

    /**
     * Find device by Id
     *
     * @param integer $id
     * @return Device
     * @throws ValidationException
     */

    public function findOrFail($id = null)
    {
        $device = $this->device->find($id);

        if (!$device) {
            throw ValidationException::withMessages(['message' => 'Could not find device.']);
        }

        return $device;
    }

    /**
     * Find device by User
     *
     * @param integer $userId
     * @return Device
     * @throws ValidationException
     */

    public function findByUser($userId = null)
    {
        $devices = $this->device->filterByUserId($userId)->get();

        if (!$devices) {
            throw ValidationException::withMessages(['message' => 'Devices not found.' ]);
        }

        return $devices;
    }

    /**
     * Create a new device.
     *
     * @param array $params
     * @return Device
     */

    public function create($params)
    {
        $device = $this->device->forceCreate($this->formatParams($params));

        return $device;
    }

    /**
     * Update given device.
     *
     * @param Device $device
     * @param array $params
     *
     * @return Device
     */
    public function update(Device $device, $params = array())
    {
        $device->name           = isset($params['name']) ? $params['name'] : $device->name;
        $device->serial_number  = isset($params['serial_number']) ? $params['serial_number'] : $device->serial_number;
        $device->status         = isset($params['status']) ? $params['status'] : $device->status;
        $device->type           = isset($params['type']) ? $params['type'] : $device->type;
        $device->description    = isset($params['description']) ? $params['description'] : $device->description;
        $device->save();

        return $device;
    }

    /**
     * Delete device.
     *
     * @param Device $device
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Device $device)
    {
        return $device->delete();
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
            'serial_number'     => isset($params['serial_number']) ? $params['serial_number'] : null,
            'status'            => isset($params['status']) ? $params['status'] : null,
            'type'              => isset($params['type']) ? $params['type'] : null,
            'description'       => isset($params['description']) ? $params['description'] : null
        ];

        return $formatted;
    }
}
