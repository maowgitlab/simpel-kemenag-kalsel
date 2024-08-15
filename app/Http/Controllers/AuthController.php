<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\VerifyMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    private function generateSuccessMessage($text)
    {
        return '<script>
            Swal.fire({
                title: "Berhasil!",
                text: "' . $text . '",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        </script>';
    }

    private function generateErrorMessage($text)
    {
        return '<script>
            Swal.fire({
                title: "Gagal!",
                text: "' . $text . '",
                icon: "error",
                showConfirmButton: true
            });
        </script>';
    }

    public function login()
    {
        if (Auth::check()) {
            return back();
        }
        return view('auth.page.login');
    }

    public function authenticate(Request $request)
    {
        // ... (Validasi dan Cek Kredensial)
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);
        if (Auth::attempt($credentials)) {
            // Login Berhasil
            $request->session()->regenerate();
            $this->user->where('username', $credentials['username'])->increment('total_login');
            $message = '<script>
                Swal.fire({
                  title: "Login Berhasil!",
                  text: "Selamat Datang Kembali ' . $credentials['username'] . '.",
                  icon: "success",
                  confirmButtonText: "OK"
                });
            </script>';
            return redirect()->intended('/dashboard')->with('message', $message);
        } else {
            // Login Gagal
            $message = '<script>
                Swal.fire({
                  title: "Login Gagal!",
                  text: "Username atau password Anda salah.",
                  icon: "error",
                  confirmButtonText: "OK"
                });
            </script>';
            return back()->with('message', $message);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            $this->user->where('username', Auth::user()->username)->update(['terakhir_login' => time()]);
            session()->invalidate();
            Auth::logout();
            $message = '<script>
                Swal.fire({
                    title: "Berhasil!",
                    text: "Anda telah logout.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            </script>';
            return redirect()->route('login')->with('message', $message);
        } else {
            return back();
        }
    }

    public function verify($username)
    {
        return view('auth.page.verify', [
            'user' => $this->user->where('username', Crypt::decrypt($username))->first(),
        ]);
    }

    public function verifyEmail($email)
    {
        $user = $this->user->where('email', $email)->first();
        if ($user->key === null) {
            $verificationCode = Str::random(6);
            $user->key = $verificationCode;
        } else {
            $user->update(['key' => null]);
            $verificationCode = Str::random(6);
            $user->key = $verificationCode;
        }
        $user->save();
        Mail::to($user->email)->send(new VerifyMail($verificationCode));
        $message = $this->generateSuccessMessage('Token verifikasi telah dikirimkan ke email Anda.');
        return back()->with('message', $message);
    }

    public function activate(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
        ], [
            'token.required' => 'Token tidak boleh kosong.',
        ]);
        $user = $this->user->where('key', $data['token'])->first();
        if ($user) {
            $user->update(['key' => null, 'status' => 'aktif', 'email_verified_at' => now()]);
            $message = $this->generateSuccessMessage('Akun Anda berhasil diaktifkan. Silahkan login kembali.');
            return redirect()->route('login')->with('message', $message);
        } else {
            $message = $this->generateErrorMessage('Token yang Anda masukkan tidak valid.');
            return back()->with('message', $message);
        }
    }
}
