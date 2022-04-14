<?php

namespace App\Http\Controllers\Api;

use App\Http\Helper;
use App\Http\Response;
use App\Models\PersonalToken;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    use Response;
    use Helper;

    public function login(Request $request) {
        $validasi = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $user = User::where('email', $request->email)->first();// query SQl (Query Builder by Laravel)
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $user->token = $this->generateToken($user->id);
                return $this->success($user);
            }
            return $this->error('Password Salah');
        }
        return $this->error('Email tidak terdaftar');
    }

    public function register(Request $request) {
        //nama, email, password
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $user = User::create(array_merge($request->all(), [
            'password' => bcrypt($request->password)
        ]));

        if ($user) {
            $user->token = $this->generateToken($user->id);
            return $this->success($user);
        }
        return $this->error('Registrasi gagal');
    }

    public function generateToken($id) {
        $token = PersonalToken::create([
            'userId' => $id,
            'token' => $this->createToken(60),
            'last_used_at' => now()
        ]);
        return $token->token;
    }

    public function daftar(Request $request) {
        //nama, email, password
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $user = User::create(array_merge($request->all(), [
            'password' => bcrypt($request->password)
        ]));

        if ($user) {
            return $this->success($user);
        }
        return $this->error('Registrasi gagal');
    }
}
