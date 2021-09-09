<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Helpers\Mapper;
use App\Helpers\ValidatorHelper;
use App\Http\Controllers\Controller;
use App\Http\IRepositories\IProductRepository;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    protected $productRepository;
    protected $requestData;

    public function __construct(IProductRepository $productRepository)
    {

        $this->productRepository = $productRepository;
        $this->requestData = Mapper::toUnderScore(Request()->all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        try {
            $data = $this->productRepository->allProducts(Auth::user()->id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$data);

        }catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        try {
            $data = $this->requestData;
            $data['user_id']= Auth::user()->id;
            $validator = Validator::make($data, $validator_rules = Product::create_update_rules, ValidatorHelper::messages() );

            if($validator->passes()){

                $model = $this->productRepository->create($data);

                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY),$model);
            }
            return JsonResponse::respondError($validator->errors()->all());

        }catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    public function show_product()
    {
        //
        try{
            $product = $this->requestData;
            $data = $this->productRepository->find( $product['product_id']);

            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$data);
        }catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function update()
    {
        //
        try{

            $data = $this->requestData;
            $data['user_id']= Auth::user()->id;
////            dd($validator_rules);
            $validator = Validator::make($data, Product::create_update_rules, ValidatorHelper::messages());
//            dd($data);

            if($validator->passes()){
                $model = $this->productRepository->update($data, $data['product_id']);

                return  JsonResponse::respondSuccess(trans(JsonResponse::MSG_UPDATED_SUCCESSFULLY));
            }

            return JsonResponse::respondError($validator->errors()->all());
        }catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        try {
//            dd($this->requestData['product_id']);
            $id = $request['product_id'];
            $this->productRepository->delete($id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_DELETED_SUCCESSFULLY));
        }catch (\Exception $e){
            dd($e);
            return  JsonResponse::respondError($e->getMessage());
        }

    }
}
