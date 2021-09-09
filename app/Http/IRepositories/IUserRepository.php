<?php


namespace App\Http\IRepositories;



interface IUserRepository
{

    public function userlogin();

    public function registerUser($input);

    public function delete($id);

    public function showUser($id);

}
