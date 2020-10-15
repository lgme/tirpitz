<?php
namespace App\Repositories;


class BaseRepository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function query($params = array())
    {
        $created_at_start_date = isset($params['created_at_start_date']) ? $params['created_at_start_date'] : null;
        $created_at_end_date   = isset($params['created_at_end_date']) ? $params['created_at_end_date'] : null;

        $query = null;

        foreach ($params as $key => $value) {
            if ($key == 'page_length' || $key == 'page' || $key == 'sort_by' || $key == 'order' || $key == 'created_at_start_date' || $key == 'created_at_end_date') continue;

            $paramValue = isset($params[$key]) ? $params[$key] : null;
            $functionName = 'filterBy' . $this->convertADAToPascalCase($key);
            $query = call_user_func(array($query != null ? $query : $this->model, $functionName), $paramValue);
        }

        $query = call_user_func(array($query != null ? $query : $this->model, 'createdAtDateBetween'), [
            'start_date' => $created_at_start_date,
            'end_date' => $created_at_end_date
        ]);

        return $query;
    }

    public function paginate($params = array())
    {
        $page_length = isset($params['page_length']) ? $params['page_length'] : config('config.page_length');
        $query = $this->query($params);

        return $query->paginate($page_length);
    }

    private function convertADAToPascalCase($name) {
        $pieces = explode("_", $name);
        $pc = '';
        if (count($pieces) > 0) {
            foreach ($pieces as $piece) {
                $pc .= ucfirst($piece);
            }
        }

        return $pc;
    }
}