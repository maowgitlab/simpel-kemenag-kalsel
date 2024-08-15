<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ThrottleComment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 3, $decayMinutes = 1440): Response
    {
      $validator = Validator::make($request->all(), [
          'media_id' => 'required',
          'perangkat' => 'required',
          'komentar' => 'required',
          'g-recaptcha-response' => 'required|captcha',
      ], [
          'komentar.required' => 'Komentar wajib diisi',
          'g-recaptcha-response.required' => 'Captcha dibutuhkan.',
          'g-recaptcha-response.captcha' => 'Validasi Captcha Gagal, Silahkan coba lagi.'
      ]);

      if ($validator->fails()) {
          return back()->withErrors($validator)->withInput();
      }

      $perangkat = $request->input('perangkat');
      $key = 'comment_' . $perangkat;
      $attempts = Cache::get($key, 0);

      if ($attempts >= $maxAttempts) {
          return back()->with('error_comment', 'Kamu sudah mencapai batas maksimal komentar hari ini. Silahkan coba kembali besok.');
      }

      Cache::put($key, $attempts + 1, $decayMinutes * 60);
      return $next($request);
    }
}
