<?php

namespace App\Http\Controllers;

use App\Services\ScoreService;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    protected $scoreService;

    public function __construct(ScoreService $scoreService)
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
        $this->scoreService = $scoreService;
    }

    /**
     * @deprecated
     * Store a new user. (make for test)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|integer',
        ]);

        try
        {
            $user = new User;
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            return response()->json($user->fresh(), 201);

        }
        catch (\Exception $e)
        {
            return response()->json( [
                'entity' => 'users',
                'action' => 'create',
                'result' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a JWT via given credentials.  (Maybe rework to Repository pattern)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'password' => 'required|integer',
            'login' => 'required|email',
        ]);

        $credentials = ['email' => $request->login, 'password' => $request->password];

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $this->scoreService->storeActivity([
            'title' => 'auth',
            'attendee_id' => auth()->user()->attendee_id
        ]);
        return $this->respondWithTokenAndData($token, auth()->user());
    }

    /**
     * Get user details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}