<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Lcobucci\JWT\Parser;

class UserController extends Controller
{
    protected $tokensExpireIn;

    public function __construct()
    {
        $this->tokensExpireIn = 15;
    }

    public function index()
    {
        //admin
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->store_id = $request->input('store_id');
        $user->email = $request->input('email');
        $user->telephone = $request->input('telephone');
        $user->password = bcrypt($request->input('password'));
        $user->active = $request->input('active');
        $user->save();

        return $user;
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //admin
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = now()->addDays($this->tokensExpireIn);
        return response()->json($tokenResult, 200);
    }

    public function logout(Request $request)
    {
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getHeader('jti');
        $token = $request->user()->tokens->find($id);

        if (!$token->revoke()) {
            $response = [
              'message' => 'Error: Logout Fail'
            ];
            return response()->json($response, 400);
        }

        $response = [
            'message' => 'Logged Out Successfully',
        ];

        return response()->json($response, 200);
    }

    public function detail()
    {
        $user = Auth::user();
        return $user;
    }
}
