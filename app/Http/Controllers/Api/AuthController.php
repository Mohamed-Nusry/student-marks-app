<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AuthService;

class AuthController extends Controller
{

    private AuthService $AuthService;

    public function __construct(AuthService $AuthService)
    {
        $this->AuthService = $AuthService;
    }

    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        $result = $this->AuthService->register($request);

        return response()->json($result, $result['status']);
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        $result = $this->AuthService->login($request);

        return response()->json($result, $result['status']);
    }
}
