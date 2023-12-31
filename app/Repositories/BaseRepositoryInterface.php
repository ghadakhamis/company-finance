<?php 

namespace App\Repositories;

/**
 * Interface BaseRepositoryInterface
 */
interface BaseRepositoryInterface
{

    /**
     * @param  array $columns
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * @param  array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param  array $data
     * @param  $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * @param  array $data
     * @param  $ids
     * @return mixed
     */
    public function updateMultiple(Array $data, Array $ids);

    /**
     * @param  $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param  $id
     * @param  array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * @param  $field
     * @param  $value
     * @param  array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = ['*']);
}
