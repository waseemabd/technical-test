<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Helpers\Mapper;
use App\Helpers\ValidatorHelper;
use App\Http\Controllers\Controller;
use App\Http\IRepositories\IUserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    protected $userRepository;
    protected $requestData;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->requestData = Mapper::toUnderScore(\Request()->all());
    }


    public function login(){
        try {

            $data = $this->userRepository->userlogin();

            if($data['msg'] == 'Authorized'){
                return JsonResponse::respondSuccess(trans(JsonResponse::MSG_SUCCESS),$data);
            }else{
                return JsonResponse::respondError(trans(JsonResponse::MSG_NOT_AUTHENTICATED),401);

            }


        } catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }


    public function register(Request $request)
    {
        try{
            $data = $this->requestData;
            $validator = Validator::make($data, $validation_rules = User::create_update_rules, ValidatorHelper::messages());

            if ($validator->passes()) {

                $user = $this->userRepository->registerUser($data);
                if($user){
                    return JsonResponse::respondSuccess(trans(JsonResponse::MSG_ADDED_SUCCESSFULLY), $user);

                }else{
                    return JsonResponse::respondError(trans(JsonResponse::MSG_FAILED));

                }
            }

            return JsonResponse::respondError($validator->errors()->all());

        }catch (\Exception $ex) {
            return JsonResponse::respondError($ex->getMessage());
        }
    }

}
