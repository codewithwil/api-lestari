<?php

namespace App\Repositories\Auth;

use App\DTOs\Auth\LoginDTO;
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    public function login(LoginDTO $data): array;
    public function logout(Request $request): void;
    public function me(Request $request): mixed;
}
