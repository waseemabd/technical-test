<?php


namespace App\Http\Repository;


use App\Helpers\JsonResponse;
use App\Http\IRepositories\IUserRepository;
use App\Models\User;
use http\Env\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function model()
    {
        return User::class;
    }

    public function userlogin()
    {

        try {

            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();
                if ($user['role'] == 2){
                    $user['role'] = 'Admin';
                }elseif ($user['role'] == 1){
                    $user['role'] = 'Seller';
                }else{
                    $user['role'] = 'Customer';
                }
                $success['user'] = $user;
                $success['token']['token'] = $user->createToken('MyApp')->accessToken;
//                dd($success['token']);
                $success['msg'] = 'Authorized';
            } else {
                $success = [];
                $success['msg'] = 'Not Authorized';
            }
            return $success;
        } catch (\Exception $exception) {
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    public function registerUser($input)
    {

        try {

            if($input['role'] == 0 || $input['role']==1){
                $input['password'] = bcrypt($input['password']);
//            $input['role'] = $input['role'];
//            dd($input);
                $user = User::create($input);
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $success['user'] = $user;
                return $success;
            }else{
                return false;
            }


        } catch (\Exception $exception) {
            throw new \Exception(trans($exception->getMessage()));
        }
    }

    public function delete($id)
    {
        try {
            return $this->model->destroy($id);
        } catch (\Exception $exception) {
            if ($exception instanceof ModelNotFoundException) {
                throw new \Exception(trans(JsonResponse::MSG_NOT_FOUND));
            }
            throw new \Exception(trans($exception->getMessage()));
        }
    }
    public function showUser($id){
        try {

            $data = $this->model->where('id',$id)->firstOrFail();
            return $data;
        }catch (\Exception $exception){
            throw new \Exception(trans($exception->getMessage()));
        }
    }
}
