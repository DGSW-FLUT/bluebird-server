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
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Verify the password and generate the token
        if (password_verify($request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user)
            ], Response::HTTP_OK);
        }
        // Bad Request response
        return response()->json([
            'error' => 'Account or password is wrong.'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function update(Request $request){
        $user = Auth::findOrFail($request->auth->id);

        $user->password = password_hash($request->input('password'), PASSWORD_DEFAULT);

        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }

    public function delete(Request $request){
        $user = Auth::findOrFail($request->auth->id);
        
        $user->destroy();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function create(Request $request){
        $input = $request->only(['account', 'password']);

        $result = Auth::where('account', $input['account'])->first();
        if($result){
            return response()->json([
                'error' => 'Account already exist.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        $user = new Auth();

        $user->account = $input['account'];
        $user->password = password_hash($input['password'], PASSWORD_DEFAULT);
        
        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }

    public function index(Request $request){
        $accounts = Auth::all();

        return response()->json($accounts, Response::HTTP_OK);
    }
}