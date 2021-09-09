<?php


namespace App\Http\Repository;


use App\Models\Profile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Helpers\JsonResponse;
use App\Helpers\Mapper;
use App\Helpers\Constants;
use Illuminate\Container\Container as App;
use App\Helpers\ResponseStatus;
use App\Http\IRepositories\IBaseRepository;

abstract class BaseRepository implements IBaseRepository
{

    /**
     * @var App
     */
    private $app;
    /**
     * @var array
     */
    private $requestData;

    /**
     * @var
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * @param App $app
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     */
    public function __construct(App $app)
    {
        try {
            $this->app = $app;
            $this->makeModel();
            $this->requestData = Mapper::toUnderScore(\Request()->all());
        } catch (\Exception $exception) {
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param array $columns
     * @param array $conditions
     * @return mixed
     * @throws \Exception
     */
    public function all($conditions = [], $columns = array('*'))
    {
        try {
            $order_by = isset($this->requestData[Constants::ORDER_BY]) ? $this->requestData[Constants::ORDER_BY] : null;
            $order_by_direction = isset($this->requestData[Constants::ORDER_By_DIRECTION]) ? $this->requestData[Constants::ORDER_By_DIRECTION] : "asc";
            $filter_operator = isset($this->requestData[Constants::FILTER_OPERATOR]) ? $this->requestData[Constants::FILTER_OPERATOR] : "=";
            $filters = $this->requestData[Constants::FILTERS] ?? [];
            $query = $this->model;
            $allConditions = array_merge($conditions, $filters);
            if (isset($order_by))
                return $query->orderBy($order_by, $order_by_direction)->filter($allConditions, $filter_operator)->get($columns);
            else
                return $query->filter($allConditions, $filter_operator)->get($columns);
        } catch (\Exception $exception) {
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param array $columns
     * @param array $conditions
     * @return mixed
     */
    public function paginate($conditions = [], $columns = array('*'))
    {

        try {
            $order_by = isset($this->requestData[Constants::ORDER_BY]) ? $this->requestData[Constants::ORDER_BY] : null;
            $order_by_direction = isset($this->requestData[Constants::ORDER_By_DIRECTION]) ? $this->requestData[Constants::ORDER_By_DIRECTION] : "asc";
            $filter_operator = isset($this->requestData[Constants::FILTER_OPERATOR]) ? $this->requestData[Constants::FILTER_OPERATOR] : "=";
            $filters = $this->requestData[Constants::FILTERS] ?? [];
            $per_page = isset($this->requestData[Constants::PER_PAGE]) ? $this->requestData[Constants::PER_PAGE] : 15;
            $query = $this->model;
            $allConditions = array_merge($conditions, $filters);
            if (isset($order_by))
                return $query->orderBy($order_by, $order_by_direction)->filter($allConditions, $filter_operator)->paginate($per_page, $columns);
            else
                return $query->filter($allConditions, $filter_operator)->paginate($per_page, $columns);
        } catch (\Exception $exception) {
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create($data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $exception) {
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     * @throws \Exception
     */
    public function update(array $data, $id, $attribute = "id")
    {
        try {
            $model_data = array();
            foreach ($this->model->getFillable() as $var) {
                if (isset($data[($var)]))
                    $model_data[$var] = $data[$var];
            }
            $modelFounded = $this->model->where($attribute, $id)->first();
            if (!isset($modelFounded)) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND), ResponseStatus::NOT_FOUND);
            }
            return $modelFounded->update($model_data);
        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param $key
     * @param $value
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function updateOrCreate($key, $value, $data)
    {
        try {
            $object = $this->findBy($key, $value);

            if (!$object)
                return $this->create($data);
            else
                return $this->update($data, $value, $key);
        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param mixed $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($model)
    {
        try {
            $this->model->where("id", $model->id)->firstOrFail();
            return $this->model->destroy($model->id);
        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws \Exception
     */
    public function find($id, $columns = array('*'))
    {
        try {
            return $this->model->findOrFail($id, $columns);
        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     * @throws \Exception
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        try {
            $attribute = Mapper::camelToSnake($attribute);
            return $this->model->where($attribute, '=', $value)->firstOrFail($columns);
        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function latestRecord()
    {
        try {
            return $this->model->latest('created_at')->firstOrFail();
        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function model();

    /**
     * @return mixed
     */
    public function makeModel()
    {
        try {
            $model = $this->app->make($this->model());
            return $this->model = $model;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

}
