<?php


namespace App\Services;


class BaseService
{
    /**
     * insert data on table
     * @param $data
     */
    public function create($data)
    {
        $model = static::$model;
        return $model::create($data);
    }

    /**
     * find row in table
     * @param $id
     */
    public function find($value, $column = 'id')
    {
        $model = static::$model;
        return $model::where($column, $value)->firstOrFail();
    }

    /**
     * update data on model
     * @param $data
     * @param $id
     */
    public function update($id, $data)
    {
        return $this->find($id)->update($data);
    }

    /**
     * update status of model
     * @param $data
     */
    public function updateStatus($id)
    {
        return $this->getQuery()
            ->where('id', $id)
            ->update(['status' => !$this->find($id)->status]);
    }

    /**
     * delete data from model
     * @param $data
     */
    public function delete($id)
    {
        return $this->getQuery()->where('id',$id)->delete();
    }


    /**
     * get model
     */
    public function model()
    {
        return static::$model;
    }

    /**
     * get model
     */
    public function newModel()
    {
        return new static::$model;
    }


    public static function make()
    {
        return new static();
    }

    public function newResourceWith($model)
    {
        $resource = static::$resource;
        return $resource::make($model);
    }

    public function newResourceCollection($collection)
    {
        $resource = static::$resource;
        return $resource::collection($collection);
    }

    public function getQuery()
    {
        return $this->newModel()->query();
    }
}
