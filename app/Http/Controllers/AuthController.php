<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\sendMail;
use Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        $email = $request->get('email');
        $data = ([
         'name' => $request->get('full_name'),
         'email' => $request->get('email'),
         'username' => $request->get('username'),
         'phone' => $request->get('phone'),
         ]);
        Mail::to($email)->send(new sendMail($data));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $currentUser = Auth::user();
        if (User::where('id', $currentUser->id)->exists()){
            $user = User::find($currentUser->id);
            $user->full_name = is_null($request->full_name) ? $currentUser->full_name : $request->full_name;
            $user->phone_number = is_null($request->phone_number) ? $currentUser->phone_number : $request->phone_number;
            $user->subscription_left = is_null($request->subscription_left) ? $currentUser->subscription_left : $request->subscription_left;
            $user->save();

            return response()->json([
              "message" => "records updated successfully",
              "request full_name" => $request->full_name
            ], 200);
        }else {
            return response()->json([
              "message" => "user not found"
            ], 404);
        }
    }

    public function deleteProfile(Request $request)
    {
        $currentUser = Auth::user();
        if(User::where('id', $currentUser->id)->exists()) {
            $user = User::find($currentUser->id);
            $user->delete();

            return response()->json([
              "message" => "records deleted"
            ], 202);
        }else {
            return response()->json([
              "message" => "user not found"
            ], 404);
      }
    }

}