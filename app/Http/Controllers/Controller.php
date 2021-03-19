<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Traits\{ValidationErrorTrait, ResponseTrait};

class Controller extends BaseController
{
    use ResponseTrait, ValidationErrorTrait;

    protected function responseWithToken($token,$data_user){
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'data_user' => $data_user
        ];
    }

  
}
