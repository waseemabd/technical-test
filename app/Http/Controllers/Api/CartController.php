<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Helpers\Mapper;
use App\Http\Controllers\Controller;
use App\Http\IRepositories\ICartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //

    protected $cartRepository;
    protected $requestData;

    public function __construct(ICartRepository $cartRepository)
    {

        $this->cartRepository = $cartRepository;
        $this->requestData = Mapper::toUnderScore(Request()->all());
    }


    public function checkout()
    {
        //

        try {
            $data = $this->cartRepository->allProductsInCart(Auth::user()->id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$data);

        }catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }

    }

    public function addToCart()
    {
        try {
            $product_id = $this->requestData['product_id'];
            $data = $this->cartRepository->addProductToCart(Auth::user()->id, $product_id);
            return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS));

        }catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function deleteFromCart()
    {
        try {
            $product_id = $this->requestData['product_id'];
            $data = $this->cartRepository->deleteProductFromCart(Auth::user()->id, $product_id);
            if($data){
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS));

            }else{
                return JsonResponse::respondError(trans(JsonResponse::MSG_NOT_FOUND));

            }

        }catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }
    }


    public function editProductInCart()
    {
        try {
            $product_id = $this->requestData['product_id'];
            $newQty = $this->requestData['qty'];
            $data = $this->cartRepository->updateProductInCart(Auth::user()->id, $product_id, $newQty);

            if($data){
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS));

            }else{
                return JsonResponse::respondError(trans(JsonResponse::MSG_NOT_FOUND));

            }

        }catch (\Exception $e){
            return JsonResponse::respondError($e->getMessage());
        }
    }


}
