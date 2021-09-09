<?php


namespace App\Http\Repository;


use App\Helpers\JsonResponse;
use App\Http\IRepositories\IProductRepository;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository extends BaseRepository implements IProductRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Product::class;
    }

    public function allProducts($user_id){
        try {
            $data = $this->model->where('user_id', $user_id)->get();
            return $data;
        }catch (\Exception $exception){
            throw new \Exception(trans($exception->getMessage()));
        }
    }


    public function delete($id){
        try {
            return $this->model->destroy($id);
        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }


}
