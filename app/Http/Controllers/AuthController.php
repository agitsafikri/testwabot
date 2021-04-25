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
            'name' => 'required|string|between:2,100',
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
         'name' => $request->get('name'),
         'email' => $request->get('email'),
         'user' => $user,
         ]);

       // Mail::to($email)->send(new sendMail($data));

        return response()->json([
            'status' => 200,
            'message' => 'User successfully registered',
            'data' => $user,
        ]);
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
        $checkAuth = auth()->check();
        if ($checkAuth == false){
            return response()->json([
                'status' => 400,
                'message' => 'Login Error',
                ]);    
        }else{
            return response()->json([
                'status' => 200,
                'message' => 'Get data user-profile success',
                'data' => auth()->user()
                ]);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $data = ([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
         ]);
       
        return response()->json([
            'status' => 200,
            'message' => 'Login success',
            'data' => $data
        ]);
    }

    public function updateProfile(Request $request)
    {
        $currentUser = Auth::user();
        if (User::where('id', $currentUser->id)->exists()){
            $user = User::find($currentUser->id);
            $user->name = is_null($request->name) ? $currentUser->name : $request->name;
            $user->phone_number = is_null($request->phone_number) ? $currentUser->phone_number : $request->phone_number;
            $user->subscription_left = is_null($request->subscription_left) ? $currentUser->subscription_left : $request->subscription_left;
            $user->save();

            return response()->json([
                "status" => 200,
                "message" => "User Profile updated successfully",
                "data" => $user
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "User not found"
            ]);
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