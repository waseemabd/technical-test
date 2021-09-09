<?php


namespace App\Http\IRepositories;


interface ICartRepository
{

    public function allProductsInCart($user_id);

    public function addProductToCart($user_id, $product_id);

    public function deleteProductFromCart($user_id, $product_id);

    public function updateProductInCart($user_id, $product_id, $newQty);

}
