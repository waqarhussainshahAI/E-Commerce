<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAdmin;
use App\Http\Requests\RegisterClient;
use App\Services\AuthClientService;

class AuthApiController extends Controller
{
    protected $authClientService;

    public function __construct(AuthClientService $authClientService)
    {
        $this->authClientService = $authClientService;

    }

    public function index()
    {
        return ['success' => 'success'];
    }

    public function login(LoginAdmin $request)
    {
        $token = $this->authClientService->login($request);

        return response()->json([
            'token' => $token,
        ], 200);
    }

    public function store(RegisterClient $request)
    {

        $user = $this->authClientService->store($request);

        return response()->json(['user' => $user], 201);
    }
}
