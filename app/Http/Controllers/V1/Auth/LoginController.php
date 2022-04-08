<?php

namespace App\Http\Controllers\V1\Auth;

use App\Helpers\Calculate;
use App\Helpers\Format;
use App\Http\Controllers\Controller;
use App\Member\Distance;
use App\User;
use App\Wilayah\Pool;
use App\Wilayah\Wilayah;
use App\Wilayah\WilayahAddressDetail;
use App\Wilayah\WilayahDetail;
use Carbon\Carbon;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public static function setUserToken($user)
    {
        //check currenttoken validated?
        if ($user->token){
            try {
                $validate_token = JWT::decode($user->token, new Key('jwt_key1', 'HS256'));
                // print_r($validate_token);die;
            } catch (ExpiredException $th) {
                $user->token = JWT::encode([
                    "id" => $user->id,
                    "username" => $user->username,
                    "iat" => 1356999524,
                    "exp" => Carbon::now()->addMinute(2)->timestamp
                ], "jwt_key1", "HS256");
            }
        }else{
            $user->token = JWT::encode([
                "id" => $user->id,
                "username" => $user->username,
                "iat" => 1356999524,
                "exp" => Carbon::now()->addMinute(2)->timestamp
            ], "jwt_key1", "HS256");
        }

        $user->last_login = Carbon::now();
        // print_r($user);die;
        $user->save();
    }

    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ], [], [
            'username' => 'No Telepon',
            'password' => 'Kata Sandi'
        ]);

        $user = User::select('*')
            ->where('username', $validated['username'])
            ->first();

        $active = true;
        $message = 'Success';

        // CHECK MEMBER STATUS
        if (!$user) {
            abort(200, 'Akun belum terdaftar.');
        } elseif ($user->password != md5($validated['password'])) {
            abort(200, 'No telepon atau kata kandi salah.');
        }

        if ($user->status == 0) {
            $active = false;
            $message = 'Akun kamu telah dinonaktifkan.';
            abort(200, $message);
        }

        unset($user->password); // remove password attribute

        // MEMBER AUTHENTICATION TOKEN
        self::setUserToken($user);

        return Format::response([
            'message' => $message,
            'active' => $active,
            'data' => $user
        ]);
    }

    public function logout()
    {
        Auth::user()->update([
            'token' => null
        ]);
        
        return Format::response([
            'message' => 'Berhasil keluar.'
        ]);
    }
}
