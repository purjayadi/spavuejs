<?php
namespace App\Http\Controllers;
use App\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function register(Request $request)
    {
        // validate

        $request->validate(
            [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]
        );
        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $token = JWTAuth::attempt($request->only('email', 'password'));
        return response()->json(['token' => "Bearer $token"]);
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $crediential=$request->only('email','password');

        if(!$token=JWTAuth::attempt($crediential)){
            return "Not Authenticated";
        }
        return ['token'=>$token];

    }
}
