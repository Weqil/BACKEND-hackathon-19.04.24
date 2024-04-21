<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\auth\RegisterRequest;
use App\Http\Requests\auth\LoginRequest;
use App\Models\CompanyInvite;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\AuthService;
use Exception;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $input = $request->all();
        $pass  =  bcrypt($input['password']);

        $authService = new AuthService();

        if($request->get("register_as") == "company"){
            $user = $authService->registerAsCompany(
                $input["email"],
                $pass,
                $input["name"],
                $request->get("company")["name"]);
        }
        else if ($request->get("register_as") == "user"){
            try {
                $user = $authService->registerAsUser(
                    $input["email"],
                    $pass,
                    $input["name"],
                    $request->get("code")
                );
            }
            catch (Exception $e){
                return response()->json(["message" => $e->getMessage()], 404);
            }
        }

        $token = $this->getAccessToken($user);

        return response()->json([
            'status'        => 'success',
            'message'       => 'register success',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user,
        ], 200);

    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->getCredentials();
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'login failed',
                'cr' => $credentials,
            ], 401);
        }

        if (isset($credentials['name'])) {
            $user = User::where('name', $credentials['name'])->firstOrFail();
        } elseif (isset($credentials['email'])) {
            $user = User::where('email', $credentials['email'])->firstOrFail();
        }

        $token = $this->getAccessToken($user);
        $cookie = Cookie::forever('Bearer_token', $token);

        $is_company = false;

        if(count($user->companies) > 0){
            $is_company = true;
        }

        return response()->json([
            'status'        => 'success',
            'message'       => 'login success',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => $user,
            "is_company" => $is_company
        ], 200)->withCookie($cookie);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $cookie = Cookie::forget('Bearer_token');
            $request->user()->currentAccessToken()->delete();
            Session::flush();

            return response()->json([
                'status'        => 'success',
                'message'       => 'logout success',
            ], 200)->withCookie($cookie);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 401);
        }
    }

    private function getAccessToken($user){
        return $user->createToken('auth_token')->plainTextToken;
    }

    private function createUserWithProfile($email, $pass, $name){
        $user  =  User::create([
            'name'=> $name,
            'password'=> $pass,
            'email' => $email,
        ]);

        $user->profile()->create();

        return $user;
    }
}
