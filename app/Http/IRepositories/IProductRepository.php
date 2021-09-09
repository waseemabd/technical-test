<?php


namespace App\Http\IRepositories;


interface IProductRepository
{

    public function allProducts($user_id);

    public function delete($id);


}
