<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use App\Http\Filters\Filter;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    /**
     * @var JsonResource | ResourceCollection
     */
    protected $resource;

    /**
     * @var array
     */
    protected $relations;

    /**
     * @var array
     */
    protected $relationsCount;

    /**
     * @var array
     */
    protected $relationsWithScopes;

    /**
     * @var Collection
     */
    protected $scopes;

    private $app;

    /**
     * @var string
     */
    protected $orderBy;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->cleanRepository();
    }

    /**
     * Set resource used in wrapping data
     *
     * @param $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Set relations needed to be eager loaded
     *
     * @param $relations
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

    /**
     * Set relations count needed to be eager loaded
     *
     * @param $relationsCount
     */
    public function setRelationsCount($relationsCount)
    {
        $this->relationsCount = $relationsCount;
    }

    /**
     * Set relations needed to be eager loaded
     *
     * @param $relations
     */
    public function setRelationsWithScopes($relations)
    {
        $this->relationsWithScopes = $relations;
    }

    /**
     * Set model scopes
     *
     * @param $scopes
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public abstract function model();

    public function getModelData(Filter $filter = null, $pagination = false, $withTrashed = false)
    {
        $model = $filter ? $this->filter($filter) : $this->model;

        $model = $this->applyScopes($model);

        $model = $model->when(
            $this->relations, function ($query, $relations) {
                return $query->with($relations);
            }
        )->when(
            $this->relationsWithScopes, function ($query, $relationsWithScopes) {
                foreach ($relationsWithScopes as $relationWithScope) {
                    $relArray = explode('-', $relationWithScope);
                    $query->with(
                        [$relArray[0]  => function ($q) use ($relArray) {
                                $q->{$relArray[1]}();
                        }]
                    );
                }
                    return $query;
            }
        )->when(
            $this->relationsCount, function ($query, $relationsCount) {
                return $query->withCount($relationsCount);
            }
        )->when(
            $this->orderBy, function ($query, $orderBy) {
                $field = explode(',', $orderBy)[0];
                $order = explode(',', $orderBy)[1];
                return $query->orderBy($field, $order);
            }
        );
        $model = $withTrashed? $model->withTrashed() : $model;
        return $pagination ? $model->paginate() : $model->get();
    }

    public function index()
    {
        $resource = $this->resource ? $this->indexResource() : $this->getModelData();
        $this->cleanRepository();
        return $resource;
    }

    public function findBy($field, $value, $columns = array('*'))
    {
        return $this->model->where($field, $value)->first($columns);
    }

    public function restoreTrashedBy($field, $value, $returnObject = true)
    {
        $this->model->where($field, $value)->onlyTrashed()->restore();
        return $returnObject? $this->findBy($field, $value) : null;
    }

    public function updateBy($field, $value, $data)
    {
        $this->model->where($field, $value)->update($data);
    }

    public function updateOrCreate($attr, $value)
    {
        return $this->model->updateOrCreate($attr, $value);
    }

    public function firstOrCreate($attr, $value)
    {
        return $this->model->firstOrCreate($attr, $value);
    }

    public function create(array $data, $force = true, $resource = true)
    {
        $model = $force ? $this->model->forceCreate($data) : $this->model->create($data);
        $resource = $resource && $this->resource ? new $this->resource($model) : $model;
        $this->cleanRepository();
        return $resource;
    }

    public function insert(array $data)
    {
        $model = $this->model->insert($data);
        $this->cleanRepository();
        return $model;
    }
    
    public function update(array $data, $id = null, $force = true, $returnFresh = true, $withTrashed = false)
    {
        if (is_null($id) and $this->model instanceof Builder) {
            $object = $withTrashed ? $this->withTrashed()->first() : $this->first();
            $model = $object;
        } elseif ($id && is_object($id) && is_subclass_of($id, Model::class)) {
            $model = $id;
            $object = $model;
        } else {
            $object = $withTrashed ? $this->withTrashed()->find($id) : $this->find($id);
            $model = $object;
        }

        $force ? $model->forceFill($data)->save() : $model->update($data);
        $object = $object->fresh();
        $this->cleanRepository();
        return $returnFresh ? $object : null;
    }

    public function deleteMultiple($column, Array $ids)
    {
        $this->model->whereIn($column, $ids)->delete();
    }

    public function updateMultiple(Array $data, Array $ids)
    {
        $this->model->whereIn('id', $ids)->update($data);
    }

    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    public function delete($id = null)
    {
        $model = null;
        if (is_array($id)) {
            $model = $this->model->destroy($id);
        } elseif (!is_null($id)) {
            $model = $this->find($id, ['*'], true)->delete();
        } elseif ($this->model instanceof Builder) {
            $model = $this->first()->delete();
        }
        $this->cleanRepository();

        return $model;
    }

    public function find($id, $columns = ['*'], $fail = true)
    {
        $method = $fail ? 'findOrFail' : 'find';
        $result = $this->model->{$method}($id, $columns);
        $this->cleanRepository();

        return $result;
    }

    protected function cleanRepository()
    {
        $this->scopes = [];
        $this->criteria = new Collection();
        $this->makeModel();
        $this->makeResource();
    }

    protected function makeModel()
    {        
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    public function makeResource()
    {
        if ($this->resource) {
            if (!is_subclass_of($this->resource, 'Illuminate\Http\Resources\Json\ResourceCollection')
                && !is_subclass_of($this->resource, 'Illuminate\Http\Resources\Json\JsonResource')
            ) {
                throw new RepositoryException(
                    "Class {$this->resource} must be an instance of 
                    Illuminate\\Http\\Resources\\Json\\ResourceCollection or
                    Illuminate\Http\Resources\Json\JsonResource"
                );
            }
        }

        return $this->resource;
    }

    protected function applyScopes($model)
    {
        $scopes = $this->scopes;
        if ($scopes) {
            foreach ($scopes as $scope => $arg) {
                if (is_int($scope)) {
                    $scope = $arg;
                    $arg = null;
                }
                $model = $model->{$scope}($arg);
            }
        }
        return $model;
    }

    private function filter(Filter $filter)
    {
        return  $this->model->filter($filter);
    }

    protected function indexResource()
    {
        return $this->resource::collection($this->getModelData());
    }
}