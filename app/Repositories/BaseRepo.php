<?php


namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseRepo
 * @package App\Repositories
 */
abstract class BaseRepo implements IRepo
{
    /**
     * @var object
     */
    protected $model;

    /**
     * BaseRepo constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = new $model;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Count All Records
     *
     * @return mixed
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * @param int $paginate
     *
     * @return mixed
     */
    public function paginate(int $paginate)
    {
        return $this->model->paginate($paginate);
    }

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param int $id
     * @return object
     */
    public function edit(int $id): object
    {
        return $this->model->where('id', $id);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function findById(int $id): object|null
    {
        return $this->model->find($id);
    }

    /**
     * @param array $clause
     * @return mixed
     */
    public function findByClause(array $clause)
    {
        return $this->model->where($clause);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param array $clause
     * @param array $data
     * @return bool
     */
    public function updateByClause(array $clause, array $data): bool
    {
        return $this->model->where($clause)->update($data);
    }

    /**
     * @param array $ids
     * @param array $data
     * @return bool
     */
    public function updateMultipleRows(array $ids, array $data): bool
    {
        return $this->model->where('id', $ids)->update($data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * @param array $clause
     * @return bool
     */
    public function deleteByClause(array $clause): bool
    {
        return $this->model->where($clause)->delete();
    }

    /**
     * @return object
     */
    public function model(): object
    {
        return $this->model;
    }

    /**
     * @return object
     */
    public function query(): object
    {
        return $this->model->query();
    }

    /**
     * @return bool
     */
    public function truncate(): bool
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->model->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return true;
    }

    /**
     * @param $where
     * @param $with
     * @param $column
     * @param $order
     * @return mixed
     */
    public function getOneWithWhere($where, $with, $column, $order)
    {
        return $this->model::where($where)->with($with)->orderBy($column, $order)->first();
    }

    /**
     * @param array $clause
     * @return mixed
     */
    public function countByClause(array $clause)
    {
        return $this->model->where($clause)->count();
    }
}
