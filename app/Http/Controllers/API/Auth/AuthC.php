<?php
namespace App\Http\Controllers\API\Auth;

use App\{
    Http\Controllers\Controller,
    DTOs\Auth\LoginDTO,
    Repositories\Auth\AuthRepositoryInterface,
};
use Illuminate\Http\Request;

class AuthC extends Controller
{
    protected AuthRepositoryInterface $auth;

    public function __construct(AuthRepositoryInterface $auth)
    {
        $this->auth = $auth;
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $dto = new LoginDTO(
            email: $validated['email'],
            password: $validated['password']
        );

        return response()->json($this->auth->login($dto));
    }

    public function me(Request $request)
    {
        return response()->json($this->auth->me($request));
    }

    public function logout(Request $request)
    {
        $this->auth->logout($request);

        return response()->json(['message' => 'Logged out']);
    }
}
