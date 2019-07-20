<?php
namespace App\Http\Controllers;
use Validator;
use App\Auth;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController 
{
    /**
     * Create a new token.
     * 
     * @param  \App\Auth   $user
     * @return string
     */
    protected function jwt(Auth $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60*24*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\Auth   $user 
     * @return mixed
     */
    public function authenticate(Request $request) {
        // Find the user by account
        $user = Auth::where('account', $request->input('account'))->first();
        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the 
            // below respose for now.
            return response()->json([
                'error' => 'Account does not exist.'
            ], 400);
        }
        // Verify the password and generate the token
        if ($user->password == $request->input('password')) {
            return response()->json([
                'token' => $this->jwt($user)
            ], 200);
        }
        // Bad Request response
        return response()->json([
            'error' => 'Account or password is wrong.'
        ], 400);
    }

    public function update(Request $request, $id){
        $user = Auth::findOrFail($id);

        $user->password = $request->input('password');

        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }

    public function delete(Request $request, $id){
        $user = Auth::findOrFail($id);
        
        $user->destroy();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function create(Request $request){
        $input = $request->only(['account', 'password']);
        $user = new Auth();

        $user->account = $input['account'];
        $user->password = $input['password'];
        
        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }
}