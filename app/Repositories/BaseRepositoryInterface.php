<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    /**
     * @param string $id
     * @return mixed
     */
    public function getById(string $id);

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param string $field
     * @param string $attribute
     * @return mixed
     */
    public function getByAttribute(string $field, string $attribute);

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * @param array $data
     * @param string $id
     * @return mixed
     */
    public function update(array $data, string $id);

    /**
     * @param string $id
     * @return mixed
     */
    public function delete(string $id);
}
