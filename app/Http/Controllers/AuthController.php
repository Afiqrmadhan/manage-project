<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login'); // Tampilkan halaman login
    }

    public function auth(Request $request)
    {
        $userModel = new UserModel();

        $username = $request->input('username');
        $password = $request->input('password');
        $password = sha1(sha1(md5($password)));

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // Periksa apakah password cocok
            if (strcmp($password, $user->password) == 0) {
                // Simpan data user ke session
                $request->session()->put([
                    'id' => $user->id,
                    'username' => $user->username,
                    'level' => $user->level, // 1 = Admin, 2 = Project Manager
                    'logged_in' => true,
                ]);

                // Redirect berdasarkan level
                if ($user->level == 1) {
                    return redirect('/admin/dashboard');
                } elseif ($user->level == 2) {
                    return redirect('/project-manager/dashboard');
                }
            } else {
                // Password salah
                return redirect('/')->with('error', 'Invalid username or password');
            }
        } else {
            // Username tidak ditemukan
            return redirect('/')->with('error', 'Invalid username or password');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }
}
