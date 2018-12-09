<?php

namespace App\Http\Controllers\Api;

use App\Branch;
use App\Camera;
use App\Http\Requests\ClientLoginFormRequest;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\UserFormRequest;
use App\User;
use App\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use App\Http\Resources\User as UserResource;
use Validator;

class UserController extends Controller
{
    protected $tokensExpireIn;

    public function __construct()
    {
        $this->tokensExpireIn = 15;
    }

    public function index()
    {
        $users = User::all();

        return $users;
    }

    public function store(UserFormRequest $request)
    {
        $u = User::where('email', '=', $request->input('email'))->first();
        $t = UserType::where('type', '=', $request->input('type'))->first();
        if ($u != null) {
            $response = [
                'message' => 'User exist',
            ];

            return response()->json($response, 400);
        } else if ($t === null) {
            $response = [
                'message' => 'User Type does not exist',
            ];

            return response()->json($response, 400);
        } else {
            $resultR = $this->handleRequest($request);
            $data = $resultR[0];
            $errors = $resultR[1];
            if (!$data) {
                $response = [
                    'message' => 'Error: Request Params Is Not Invalid',
                    'errors' => $errors,
                ];
                return response()->json($response, 400);
            }

            $user = User::create($data);
            $user->active = false;
            $user->assignRole($request->input('roles'));

            if (!$user) {
                $response = [
                    'message' => 'Error: Create User Fail'
                ];
                return response()->json($response, 404);
            }

            return (new UserResource($user))
                ->additional([
                    'info' => [
                        'message' => 'User Created Successfully',
                        'version' => '1.0'
                    ],
                    'redirect' => route('register-help.index'),
                ])
                ->response()
                ->setStatusCode(201);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $userRole = $user->roles()->get();
        $data = [
            'user' => $user,
            'roles' => $userRole,
        ];

        return $data;
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //admin
    }

    public function handleRequest(UserFormRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $validator = Validator::make($data, $request->setRules());
        if ($validator->fails()) {
            return [false, $validator->errors()];
        }
        return [$data, $validator->errors()];
    }

    public function handleLoginRequest(LoginFormRequest $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, $request->setRules());
        if ($validator->fails()) {
            return [false, $validator->errors()];
        }
        return [$data, $validator->errors()];
    }

    public function login(LoginFormRequest $request)
    {
        $checkCam = false;
        $resultR = $this->handleLoginRequest($request);
        $data = $resultR[0];
        $errors = $resultR[1];
        if (!$data) {
            $response = [
                'message' => 'Error: Request Params Is Not Invalid',
                'errors' => $errors,
                'redirect' => route('login-help.index'),
            ];
            return response()->json($response, 400);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized',
                'redirect' => route('login-help.index'),
            ], 401);

        $user = $request->user();

        if ($user->isActive() == false) {
            return response()->json([
                'message' => 'Acount Not Active',
                'redirect' => route('login-help.index'),
            ], 401);
        }

        if (($user->type == 'branch') && ($request->has('camera_id'))) {
            $branch = Branch::where('user_id', '=', $user->id)->first();
            $cameras = $branch->cameras()->get();
            foreach ($cameras as $camera) {
                if ($camera->id == $data['camera_id']) $checkCam = true;
            }
            if ($checkCam == false) {
                return response()->json([
                    'message' => 'Login fail! Camera_id '
                        . $data['camera_id']
                        . ' does not exist in Branch_id '
                        . $branch->id,
                    'redirect' => route('login-help.index'),
                ], 400);
            }
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = now()->addDays($this->tokensExpireIn);

        if ($user->type == 'branch') {
            if ($request->has('camera_id')) {
                $response = [
                    'message' => 'Login successfully',
                    'type' => $user->type,
                    'camera_id' => $camera->id,
                    'branch_id' => $user->branch->id,
                    'store_id' => $user->branch->store_id,
                    'access_token' => $tokenResult->accessToken,
                    'expires_at' => $token->expires_at->format('Y-m-d H:i:s'),
                    'redirect' => route('documents.index'),
                ];
            } else {
                $response = [
                    'message' => 'Login successfully',
                    'type' => $user->type,
                    'branch_id' => $user->branch->id,
                    'store_id' => $user->branch->store_id,
                    'access_token' => $tokenResult->accessToken,
                    'expires_at' => $token->expires_at->format('Y-m-d H:i:s'),
                    'redirect' => route('documents.index'),
                ];
            }
        } elseif ($user->type == 'store') {
            $response = [
                'message' => 'Login successfully',
                'type' => $user->type,
                'store_id' => $user->store->id,
                'branches' => $user->store->branches,
                'access_token' => $tokenResult->accessToken,
                'expires_at' => $token->expires_at->format('Y-m-d H:i:s'),
                'redirect' => route('documents.index'),
            ];
        } else {
            $response = [
                'message' => 'Login successfully',
                'type' => $user->type,
                "access_token" => $tokenResult->accessToken,
                "expires_at" => $token->expires_at->format('Y-m-d H:i:s'),
                'redirect' => route('documents.index'),
            ];
        }
        return response()->json($response, 200);
    }

    public function logout(LoginFormRequest $request)
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

    public function clientLogin(ClientLoginFormRequest $request)
    {
        $data = $request->all();
        $branch = Branch::findOrFail($data['branch_id']);
        $user = User::findOrFail($branch->user_id);

        $tokenResult = $user->createToken('Personal Access Token');

        $response = [
            'message' => 'Get login for client successfully',
            'webservice_token' => $tokenResult->accessToken,
        ];
        return response()->json($response, 200);
    }
}
