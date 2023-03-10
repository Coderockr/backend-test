<?php

namespace App\Repositories;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @param string $id
     * @return mixed
     */
    public function getById(string $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param string $field
     * @param string $attribute
     * @return mixed
     */
    public function getByAttribute(string $field, string $attribute)
    {
        return $this->model->where($field, $attribute);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param string $id
     * @return mixed
     */
    public function update(array $data, string $id)
    {
        return $this->model->where('id', $id)
            ->update($data);
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function delete(string $id)
    {
        return $this->model->where('id', $id)
            ->delete();
    }
}
