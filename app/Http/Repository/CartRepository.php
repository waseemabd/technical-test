<?php


namespace App\Http\Repository;


use App\Helpers\JsonResponse;
use App\Http\IRepositories\ICartRepository;
use App\Models\Cart;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CartRepository extends BaseRepository implements ICartRepository
{

    public function model()
    {
        return Cart::class;
    }

    public function allProductsInCart($user_id){
        try {
            $cart = $this->model->where('user_id', $user_id)->firstOrFail();
            $products_arr = [];
            $total = 0;
            foreach ($cart->products as $product){

                $product['totalPrice'] = $product->price * $product->pivot->qty;
                $product['quantity'] = $product->pivot->qty;
                $total += $product['totalPrice'];
                array_push($products_arr, $product);
            }

            return ['products'=> $products_arr, 'total'=> $total];
        }catch (\Exception $exception){
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    public function addProductToCart($user_id, $product_id){
        try {
            $cart = $this->model->where('user_id', $user_id)->with('products')->firstOrFail();

            $productsIds = [];
            $cartProducts =$cart->products;

            //  get all product ids for customer cart
            foreach ($cartProducts as $product){
                array_push($productsIds, $product->id);
            }

            if (in_array($product_id, $productsIds)){ //  check if the product is already exist in the cart
                foreach ($cartProducts as $product){

                    if($product->id == $product_id){ //  stop at the target record of $product_id

                        //  increase the qty and update target record
                        $product->pivot->qty++;
                        $cart->products()->updateExistingPivot($product_id, ['qty' => $product->pivot->qty++]);

                        return true;
                    }

                }
            }else{ //  if the product is not exist in the cart

                // add the product to the cart
                $cart->products()->attach($product_id, ['qty' => 1]);

                return true;
            }

        }catch (\Exception $exception){
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    public function deleteProductFromCart($user_id, $product_id){
        try {
            $cart = $this->model->where('user_id', $user_id)->with('products')->firstOrFail();

            $productsIds = [];
            $cartProducts =$cart->products;

            //  get all product ids for customer cart
            foreach ($cartProducts as $product){
                array_push($productsIds, $product->id);
            }

            if (in_array($product_id, $productsIds)){ //  check if the product is already exist in the cart
                $cart->products()->detach($product_id);
                return true;
            }else{
                return false;
            }

        }catch (\Exception $exception){
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    public function updateProductInCart($user_id, $product_id, $newQty){
        try {
            $cart = $this->model->where('user_id', $user_id)->with('products')->firstOrFail();

            $productsIds = [];
            $cartProducts = $cart->products;

            //  get all product ids for customer cart
            foreach ($cartProducts as $product){
                array_push($productsIds, $product->id);
            }

            if (in_array($product_id, $productsIds)){ //  check if the product is already exist in the cart

                foreach ($cartProducts as $product){

                    if($product->id == $product_id){ //  stop at the target record of $product_id

                        //  replace the qty with new value and update target record
                        if($newQty >= 0){
                            $product->pivot->qty = $newQty;
                        }else{      // if the value is negative
                            $product->pivot->qty = 0;
                        }

                        $cart->products()->updateExistingPivot($product_id, ['qty' => $product->pivot->qty]);

                        return true;
                    }

                }
            }else{ //  if the product is not exist in the cart

                return false;
            }

        }catch (\Exception $exception){
            throw new \Exception(trans($exception->getMessage()));
        }
    }
}
