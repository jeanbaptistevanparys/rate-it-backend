<?php

namespace App\Http\Controllers;

use App\Modules\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AuthController extends Controller
{
    private $_service;
    public function __construct(UserService $service)
    {
        $this->_service = $service;
    }

    public function register(Request $request) : Response
    {
        $this->_service->registerUser($request->all());

        if ($this->_service->hasErrors()) {
            return response([
                'status' => 400,
                'message' => 'Unable to add user',
                'errors' => $this->_service->getErrors()], 400);
        }

        return response()->noContent();
    }

    public function login(Request $request) : Response
    {
        $data = $request->all();
        $token = $this->_service->login($data);
        
        if ($this->_service->hasErrors()) {
            return response([
                'status' => 400,
                'message' => 'Unable to login',
                'errors' => $this->_service->getErrors()], 401);
        }

        if (is_null($token)) {
            return response([
                'status' => 401,
                'message' => 'Credentials are invalid',
                'errors' => []], 401);
        }


        return response([
            "status" => "success",
            "token" => $token
        ], 200);
    }
    
}
