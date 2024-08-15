<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Media;
use App\Models\Comment;
use App\Models\Service;
use App\Models\Category;
use App\Models\ListService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\ServiceApplicant;
use App\Mail\ApplicantStatusMail;
use App\Models\Feedback;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;

class CmsController extends Controller
{
    protected $media, $category, $tag,
        $user, $listService, $feedback,
        $service, $serviceApplicant, $comment;

    public function __construct(Media $media, Category $category, Tag $tag, User $user, ListService $listService, Service $service, ServiceApplicant $serviceApplicant, Comment $comment, Feedback $feedback)
    {
        $this->media = $media;
        $this->category = $category;
        $this->tag = $tag;
        $this->user = $user;
        $this->listService = $listService;
        $this->service = $service;
        $this->serviceApplicant = $serviceApplicant;
        $this->comment = $comment;
        $this->feedback = $feedback;
    }

    private function sendTelegramNotification($media)
    {
        $telegram_token = env('TELEGRAM_TOKEN', 'TODO');
        $chat_id = env('TELEGRAM_CHANNEL', 'TODO');

        // Path lokal dari gambar yang akan diunggah
        $image_path = storage_path('app/public/' . $media->gambar);

        $tags = '';
        foreach ($media->tags->unique('id') as $tag) {
            $tags .= '#' . $tag->nama_tag . ', ';
        }

        $response = Http::attach('photo', file_get_contents($image_path), 'photo.jpg')
            ->post("https://api.telegram.org/bot{$telegram_token}/sendPhoto", [
                'chat_id' => $chat_id,
                'caption' => "<b>Judul:</b> " . $media->judul . "\n"
                    . "<b>Hari/Tanggal:</b> " . "<u>" . $media->created_at->translatedFormat('l, d F Y H:i') . " WITA" . "</u>" . "\n"
                    . "<b>Media:</b> " . $media->category->nama_kategori . "\n"
                    . "<b>Tagar:</b> " . $tags . "\n"
                    . "<b>Diupload Oleh:</b> " . $media->user->username . "\n"
                    . "<b>Baca selengkapnya:</b> " . "<a href='" . route('home', ['media' => $media->slug]) . "'>Disini</a>",
                'parse_mode' => 'HTML',
            ]);

        $responseBody = $response->json();
        if (isset($responseBody['result']['message_id'])) {
            $media->telegram_msg_id = $responseBody['result']['message_id'];
            $media->save();

            if ($media->pilihan == 1) {
                $this->pinTelegramMessage($chat_id, $media->telegram_msg_id, $telegram_token);
            }
        }

        Log::info("Telegram Response Body: " . $response->body());

        return $response;
    }

    private function pinTelegramMessage($chat_id, $message_id, $telegram_token)
    {
        $response = Http::post("https://api.telegram.org/bot{$telegram_token}/pinChatMessage", [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'disable_notification' => true,
        ]);

        Log::info("Telegram Pin Response Body: " . $response->body());

        return $response;
    }

    private function unpinTelegramMessage($chat_id, $message_id, $telegram_token)
    {
        $response = Http::post("https://api.telegram.org/bot{$telegram_token}/unpinChatMessage", [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);

        Log::info("Telegram Unpin Response Body: " . $response->body());

        return $response;
    }

    private function editTelegramNotification($media)
    {
        $telegram_token = env('TELEGRAM_TOKEN', 'TODO');
        $chat_id = env('TELEGRAM_CHANNEL', 'TODO');
        $message_id = $media->telegram_msg_id;

        // Path lokal dari gambar yang akan diunggah
        $image_path = storage_path('app/public/' . $media->gambar);

        // Hapus pesan lama
        $deleteResponse = Http::post("https://api.telegram.org/bot{$telegram_token}/deleteMessage", [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);

        // Periksa jika pesan berhasil dihapus
        if ($deleteResponse->successful()) {
            $tags = '';
            foreach ($media->tags->unique('id') as $tag) {
                $tags .= '#' . $tag->nama_tag . ', ';
            }

            // Kirim pesan baru dengan foto dan caption
            $sendResponse = Http::attach('photo', file_get_contents($image_path), 'photo.jpg')
                ->post("https://api.telegram.org/bot{$telegram_token}/sendPhoto", [
                    'chat_id' => $chat_id,
                    'caption' => "<b>Judul:</b> " . $media->judul . "\n"
                        . "<b>Hari/Tanggal:</b> " . "<u>" . $media->created_at->translatedFormat('l, d F Y H:i') . " WITA" . "</u>" . "\n"
                        . "<b>Media:</b> " . $media->category->nama_kategori . "\n"
                        . "<b>Tagar:</b> " . $tags . "\n"
                        . "<b>Diupload Oleh:</b> " . $media->user->username . "\n"
                        . "<b>Baca selengkapnya:</b> " . "<a href='" . route('home', ['media' => $media->slug]) . "'>Disini</a>",
                    'parse_mode' => 'HTML',
                ]);

            // Periksa jika pesan baru berhasil dikirim
            if ($sendResponse->successful()) {
                // Simpan message_id baru ke dalam database
                $newMessageId = $sendResponse->json('result.message_id');
                $media->telegram_msg_id = $newMessageId;
                $media->save();

                // Pin atau unpin pesan baru jika diperlukan
                if ($media->pilihan == 1) {
                    $this->pinTelegramMessage($chat_id, $newMessageId, $telegram_token);
                } else {
                    $this->unpinTelegramMessage($chat_id, $newMessageId, $telegram_token);
                }

                Log::info("Telegram Send Response Body: " . $sendResponse->body());

                return $sendResponse;
            } else {
                Log::error("Failed to send new Telegram message: " . $sendResponse->body());
            }
        } else {
            Log::error("Failed to delete old Telegram message: " . $deleteResponse->body());
        }

        return null;
    }

    private function deleteTelegramNotification($media)
    {
        $telegram_token = env('TELEGRAM_TOKEN', 'TODO');
        $chat_id = env('TELEGRAM_CHANNEL', 'TODO');
        $message_id = $media->telegram_msg_id;

        $response = Http::post("https://api.telegram.org/bot{$telegram_token}/deleteMessage", [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);

        Log::info("Telegram Delete Response Body: " . $response->body());

        return $response;
    }

    private function resizeAndSaveImage($image, $path, $width = null, $height = null, $quality = null)
    {
        // Generate a filename based on the current time and the original extension
        $filename = time() . '.' . $image->getClientOriginalExtension();

        // Resize the image
        $imageResized = Image::make($image);
        $imageResized->resize($width, $height);

        // Save the image to the specified path with the given quality
        $imageResized->save(storage_path('app/public/' . $path . '/' . $filename), $quality);

        return $path . '/' . $filename;
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

    public function dashboard()
    {
        session(['previousUrl' => 'dashboard']);
        return view('cms.page.dashboard', [
            'recentActivities' => $this->media->select('user_id', 'konten', 'judul', 'created_at', 'gambar')->with('user')->latest()->take(8)->get(),
            'popularMedias' => $this->media->select('judul', 'jumlah_dibaca')->take(5)->where('jumlah_dibaca', '>=', 2)->get(),
            'popularCategories' => $this->category->select('nama_kategori')->withCount('medias')->having('medias_count', '>=', 2)->take(5)->get(),
            'trendingService' => $this->listService->select('judul', 'jumlah_pengajuan')->get(),
            'totalMedias' => $this->media->count(),
            'totalCategories' => $this->category->count(),
            'totalUsers' => $this->user->count(),
            'totalServiceApplicant' => $this->serviceApplicant->count(),
        ]);
    }

    public function profile()
    {
        return view('cms.page.profile', [
            'user' => $this->user->find(Auth::user()->id),
        ]);
    }

    
    public function deleteAccount($id)
    {
        $user = $this->user->findOrFail($id);
        if ($user->avatar != 'user.png') {
            unlink(storage_path('app/public/' . $user->avatar));
        }
        $user->delete();
        $message = $this->generateSuccessMessage('Akun berhasil dihapus.');
        return redirect()->route('login')->with('message', $message);
    }

    public function validatePassword(Request $request)
    {
        if (Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['valid' => true]);
        } else {
            return response()->json(['valid' => false]);
        }
    }

    public function profileUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required',
            'profil' => 'image|mimes:png,jpg,jpeg',
            'tentang' => 'required',
            'email' => [
                'required',
                'email',
                'regex:/^[^\s@]+@(gmail\.com|yahoo\.com|outlook\.com)$/',
                Rule::unique('users', 'email')->ignore($id),
            ],
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'profil.image' => 'File harus berupa gambar.',
            'profil.mimes' => 'Gambar harus memiliki format: png, jpg, jpeg.',
            'tentang.required' => 'Tentang wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.regex' => 'Email harus menggunakan domain gmail, yahoo, atau outlook.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
        ]);
        $user = $this->user->findOrFail($id);
        if ($request->hasFile('profil')) {
            if ($user->avatar == 'user.png') {
                $image = $request->file('profil');
                $path = 'img/user';
                $filename = $this->resizeAndSaveImage($image, $path, 32, 32, 75);

                $user->update([
                    'nama' => ucwords($data['nama_lengkap']),
                    'avatar' => $filename,
                    'bio' => $data['tentang'],
                    'email' => $data['email'],
                ]);
                $message = $this->generateSuccessMessage('Profil berhasil diupdate.');
                return redirect()->route('profile')->with('message', $message);
            } else {
                unlink(storage_path('app/public/' . $user->avatar));
                $image = $request->file('profil');
                $path = 'img/user';
                $filename = $this->resizeAndSaveImage($image, $path, 100, 100, 75);

                $user->update([
                    'nama' => ucwords($data['nama_lengkap']),
                    'avatar' => $filename,
                    'bio' => $data['tentang'],
                    'email' => $data['email'],
                ]);
                $message = $this->generateSuccessMessage('Profil berhasil diupdate.');
                return redirect()->route('profile')->with('message', $message);
            }
        } else {
            $user->update([
                'nama' => ucwords($data['nama_lengkap']),
                'bio' => $data['tentang'],
                'email' => $data['email'],
            ]);
            $message = $this->generateSuccessMessage('Profil berhasil diupdate.');
            return redirect()->route('profile')->with('message', $message);
        }
    }

    public function removeProfile($id)
    {
        $user = $this->user->findOrFail($id);
        unlink(storage_path('app/public/' . $user->avatar));
        $user->update([
            'avatar' => 'user.png',
        ]);
        return redirect()->back();
    }

    public function changePassword(Request $request, $id)
    {
        $data = $request->validate([
            'currentpassword' => 'required',
            'password' => 'required|confirmed|min:5',
        ], [
            'currentpassword.required' => 'Password lama tidak boleh kosong.',
            'password.required' => 'Password baru tidak boleh kosong.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password baru minimal 5 huruf.',
        ]);

        $user = $this->user->findOrFail($id);
        if (Hash::check($data['currentpassword'], $user->password)) {
            if (strcmp($data['currentpassword'], $data['password']) != 0) {
                $this->user->where('id', $user->id)->update(['password' => Hash::make($data['password'])]);
                Auth::logout();
                $message = $this->generateSuccessMessage('Password berhasil diubah. Silahkan login kembali.');
                return redirect()->route('login')->with('message', $message);
            } else {
                $message = $this->generateErrorMessage('Password baru tidak boleh sama dengan password sekarang.');
                return back()->with('message', $message);
            }
        } else {
            $message = $this->generateErrorMessage('Password lama tidak sesuai.');
            return redirect()->back()->with('message', $message);
        }
    }

    public function media()
    {
        return view('cms.page.media.index', [
            'medias' => $this->media->with('category', 'tags')
                ->where('user_id', Auth::user()->id)
                ->latest()
                ->get(),
        ]);
    }

    public function createMedia()
    {
        return view('cms.page.media.create', [
            'categories' => $this->category->select('id', 'nama_kategori')->get(),
            'tags' => $this->tag->select('id', 'nama_tag')->get(),
        ]);
    }

    public function mediaStore(Request $request)
    {
        $request->validate([
            'judul' => 'required|unique:media,judul',
            'gambar' => 'required|image|mimes:png,jpg,jpeg',
            'konten' => 'required',
            'kategori' => 'required',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'judul.required' => 'judul wajib diisi.',
            'judul.unique' => 'Judul sudah pernah digunakan.',
            'gambar.required' => 'gambar wajib dipilih.',
            'gambar.image' => 'hanya gambar yang boleh diupload.',
            'gambar.mimes' => 'gambar harus memiliki format png, jpg, atau jpeg.',
            'konten.required' => 'konten wajib diisi.',
            'kategori.required' => 'kategori wajib dipilih.',
            'tags.required' => 'tag wajib dipilih.',
            'tags.array' => 'tag harus berupa array.',
            'tags.*.exists' => 'Salah satu tag yang dipilih tidak valid.',
        ]);

        $image = $request->file('gambar');
        $path = 'img/media';
        $filename = $this->resizeAndSaveImage($image, $path, 960, 540, 75);

        $media = $this->media->create([
            'user_id' => Auth::user()->id,
            'category_id' => $request->post('kategori'),
            'judul' => $judul = ucwords(Str::lower($request->post('judul'))),
            'slug' => Str::slug($judul),
            'konten' => $request->post('konten'),
            'gambar' => $filename,
            'pilihan' => $request->has('pilihan') ? true : false,
        ]);
        $media->tags()->attach($request->post('tags'));
        foreach ($request->post('tags') as $tagId) {
            $tag = Tag::find($tagId);
            if ($tag) {
                $tag->increment('jumlah_penggunaan');
            }
        }
        $this->sendTelegramNotification($media);
        $message = $this->generateSuccessMessage('Media berhasil diupload');
        return redirect()->route('media')->with('message', $message);
    }

    public function editMedia($slug)
    {
        $media = $this->media->where('slug', $slug)->firstOrFail();
        $categories = $this->category->select('id', 'nama_kategori')->get();
        $tags = $this->tag->select('id', 'nama_tag')->get();
        return view('cms.page.media.edit', compact('categories', 'tags', 'media'));
    }

    public function reuploadMedia(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|unique:media,judul,' . $id,
            'gambar' => 'image|mimes:png,jpg,jpeg',
            'konten' => 'required',
            'kategori' => 'required',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'judul.required' => 'judul wajib diisi.',
            'judul.unique' => 'Judul sudah pernah digunakan.',
            'gambar.image' => 'hanya gambar yang boleh diupload.',
            'gambar.mimes' => 'gambar harus memiliki format png, jpg, atau jpeg.',
            'konten.required' => 'konten wajib diisi.',
            'kategori.required' => 'kategori wajib dipilih.',
            'tags.required' => 'tag wajib dipilih.',
            'tags.array' => 'tag harus berupa array.',
            'tags.*.exists' => 'Salah satu tag yang dipilih tidak valid.',
        ]);

        $media = $this->media->findOrFail($id);
        if ($request->hasFile('gambar')) {
            unlink(storage_path('app/public/' . $media->gambar));
            $image = $request->file('gambar');
            $path = 'img/media';
            $filename = $this->resizeAndSaveImage($image, $path, 960, 540, 75);

            $media->update([
                'user_id' => Auth::user()->id,
                'category_id' => $request->post('kategori'),
                'judul' => $judul = ucwords(Str::lower($request->post('judul'))),
                'slug' => Str::slug($judul),
                'konten' => $request->post('konten'),
                'gambar' => $filename,
                'pilihan' => $request->has('pilihan') ? true : false,
            ]);
            $media->tags()->sync($request->post('tags'));
            $this->editTelegramNotification($media);
            $message = $this->generateSuccessMessage('Media berhasil diupdate.');
            return redirect()->route('media')->with('message', $message);
        } else {
            $media->update([
                'user_id' => Auth::user()->id,
                'category_id' => $request->post('kategori'),
                'judul' => $judul = ucwords(Str::lower($request->post('judul'))),
                'slug' => Str::slug($judul),
                'konten' => $request->post('konten'),
                'pilihan' => $request->has('pilihan') ? true : false,
            ]);
            $media->tags()->sync($request->post('tags'));
            $this->editTelegramNotification($media);
            $message = $this->generateSuccessMessage('Media berhasil diupdate.');
            return redirect()->route('media')->with('message', $message);
        }
    }

    public function deleteMedia($id)
    {
        $media = $this->media->findOrFail($id);
        if ($media->gambar) {
            unlink(storage_path('app/public/' . $media->gambar));
        }
        $this->deleteTelegramNotification($media);
        $media->delete();
        $message = $this->generateSuccessMessage('Media berhasil dihapus.');
        return redirect()->route('media')->with('message', $message);
    }

    
    public function category()
    {
        return view('cms.page.category.index', [
            'categories' => $this->category->orderByDesc('created_at')->get(),
        ]);
    }

    public function createCategory()
    {
        return view('cms.page.category.create');
    }

    public function categoryStore(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|unique:categories,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama Kategori harus diisi.',
            'nama_kategori.unique' => 'Nama Kategori sudah ada.',
        ]);
        $this->category->create([
            'nama_kategori' => $slug = ucwords(Str::lower($data['nama_kategori'])),
            'slug' => Str::slug($slug),
        ]);
        $message = $this->generateSuccessMessage('Kategori berhasil ditambahkan.');
        return redirect()->route('category')->with('message', $message);
    }

    public function deleteCategory($id)
    {
        $category = $this->category->findOrFail($id);
        $category->delete();
        $message = $this->generateSuccessMessage('Kategori berhasil dihapus.');
        return redirect()->route('category')->with('message', $message);
    }

    public function editCategory($id)
    {
        return view('cms.page.category.edit', [
            'category' => $this->category->findOrFail($id),
        ]);
    }

    public function updateCategory(Request $request, $id)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|unique:categories,nama_kategori,' . $id,
        ], [
            'nama_kategori.required' => 'Nama Kategori harus diisi.',
            'nama_kategori.unique' => 'Nama Kategori sudah ada.',
        ]);
        $category = $this->category->findOrFail($id);
        $category->update([
            'nama_kategori' => $slug = ucwords(Str::lower($data['nama_kategori'])),
            'slug' => Str::slug($slug),
        ]);
        $message = $this->generateSuccessMessage('Kategori berhasil diupdate.');
        return redirect()->route('category')->with('message', $message);
    }

    public function tag()
    {
        return view('cms.page.tag.index', [
            'tags' => $this->tag->orderByDesc('created_at')->get(),
        ]);
    }

    public function createTag()
    {
        session(['previousUrl' => 'input-tag']);
        return view('cms.page.tag.create');
    }

    public function tagStore(Request $request)
    {
        $data = $request->validate([
            'nama_tag' => 'required|unique:tags,nama_tag|max:15',
        ], [
            'nama_tag.required' => 'Nama Tag harus diisi.',
            'nama_tag.unique' => 'Nama Tag sudah ada.',
            'nama_tag.max' => 'Nama Tag terlalu panjang.',
        ]);
        $this->tag->create([
            'nama_tag' => $slug = Str::replace(' ', '', $data['nama_tag']),
            'slug' => Str::slug($slug),
        ]);
        $message = $this->generateSuccessMessage('Tag berhasil ditambahkan.');
        $previousUrl = session('previousUrl');
        if ($previousUrl == 'input-tag') {
            return redirect()->route('tag')->with('message', $message);
        } else {
            return redirect()->back()->with('message', $message);
        }
    }

    public function deleteTag($id)
    {
        $tag = $this->tag->findOrFail($id);
        $tag->delete();
        $message = $this->generateSuccessMessage('Tag berhasil dihapus.');
        return redirect()->route('tag')->with('message', $message);
    }

    public function editTag($id)
    {
        return view('cms.page.tag.edit', [
            'tag' => $this->tag->findOrFail($id),
        ]);
    }

    public function updateTag(Request $request, $id)
    {
        $data = $request->validate([
            'nama_tag' => 'required||max:15|unique:tags,nama_tag,' . $id,
        ], [
            'nama_tag.required' => 'Nama Tag harus diisi.',
            'nama_tag.unique' => 'Nama Tag sudah ada.',
            'nama_tag.max' => 'Nama Tag terlalu panjang.',
        ]);
        $tag = $this->tag->findOrFail($id);
        $tag->update([
            'nama_tag' => $slug = Str::replace(' ', '', $data['nama_tag']),
            'slug' => Str::slug($slug),
        ]);
        $message = $this->generateSuccessMessage('Tag berhasil diupdate.');
        return redirect()->route('tag')->with('message', $message);
    }

    public function user()
    {
        return view('cms.page.user.index', [
            'users' => $this->user->orderByDesc('created_at')->get(),
        ]);
    }

    public function deleteUser($id)
    {
        $user = $this->user->findOrFail($id);
        if ($user->avatar != 'user.png') {
            unlink(storage_path('app/public/' . $user->avatar));
        }
        $user->delete();
        $message = $this->generateSuccessMessage('User berhasil dihapus.');
        return redirect()->route('user')->with('message', $message);
    }

    public function createUser()
    {
        return view('cms.page.user.create');
    }

    public function userStore(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|unique:users,username',
            'email' => [
                'required',
                'unique:users,email',
                'email',
                'regex:/^[^\s@]+@(gmail\.com|yahoo\.com|outlook\.com)$/'
            ],
            'role' => 'required|in:admin,penulis',
            'status' => 'required|in:aktif,non-aktif',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah ada.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah ada.',
            'email.email' => 'Email tidak valid.',
            'email.regex' => 'Email harus menggunakan domain gmail.com, yahoo.com, atau outlook.com.',
            'role.required' => 'Role harus diisi.',
            'role.in' => 'Role tidak valid.',
            'status.required' => 'Status harus diisi.',
            'status.in' => 'Status tidak valid.',
        ]);
        $this->user->create([
            'username' => $data['username'],
            'role' => $data['role'],
            'status' => $data['status'],
            'password' => Hash::make('12345'),
            'email' => $data['email'],
        ]);
        $message = $this->generateSuccessMessage('User berhasil ditambahkan.');
        return redirect()->route('user')->with('message', $message);
    }

    public function editUser($id)
    {
        return view('cms.page.user.edit', [
            'user' => $this->user->findOrFail($id),
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $data = $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            'role' => 'required|in:admin,penulis',
            'status' => 'required|in:aktif,non-aktif',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah ada.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah ada.',
            'email.email' => 'Email tidak valid.',
            'role.required' => 'Role harus diisi.',
            'role.in' => 'Role tidak valid.',
            'status.required' => 'Status harus diisi.',
            'status.in' => 'Status tidak valid.',
        ]);
        $user = $this->user->findOrFail($id);
        $user->update([
            'username' => $data['username'],
            'role' => $data['role'],
            'status' => $data['status'],
            'email' => $data['email'],
        ]);
        $message = $this->generateSuccessMessage('User berhasil diupdate.');
        return redirect()->route('user')->with('message', $message);
    }

    public function listService()
    {
        return view('cms.page.services.listService.index', [
            'listServices' => $this->listService->latest()->get(),
        ]);
    }

    public function createListService()
    {
        return view('cms.page.services.listService.create');
    }

    public function listServiceStore(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|unique:list_services,judul',
        ], [
            'judul.required' => 'Judul tidak boleh kosong.',
            'judul.unique' => 'Judul sudah ada. ',
        ]);
        $this->listService->create([
            'judul' => ucwords($data['judul']),
        ]);
        $message = $this->generateSuccessMessage('Kategori Pelayanan berhasil dibuat.');
        return redirect()->route('listService')->with('message', $message);
    }

    public function editListService($id)
    {
        return view('cms.page.services.listService.edit', [
            'listService' => $this->listService->findOrFail($id),
        ]);
    }

    public function updateListService(Request $request, $id)
    {
        $data = $request->validate([
            'judul' => 'required|unique:list_services,judul,' . $id,
        ], [
            'judul.required' => 'Judul tidak boleh kosong.',
            'judul.unique' => 'Judul sudah ada. ',
        ]);
        $listService = $this->listService->findOrFail($id);
        $listService->update([
            'judul' => ucwords($data['judul']),
        ]);
        $message = $this->generateSuccessMessage('Kategori Pelayanan berhasil diupdate.');
        return redirect()->route('listService')->with('message', $message);
    }

    public function deleteListService($id)
    {
        $listService = $this->listService->findOrFail($id);
        $listService->delete();
        $message = $this->generateSuccessMessage('Kategori Pelayanan berhasil dihapus.');
        return redirect()->route('listService')->with('message', $message);
    }

    public function service()
    {
        return view('cms.page.services.service.index', [
            'services' => $this->service->with('list')->latest()->get(),
        ]);
    }

    public function createService()
    {
        return view('cms.page.services.service.create', [
            'listServices' => $this->listService->latest()->get(),
        ]);
    }

    public function serviceStore(Request $request)
    {
        $data = $request->validate([
            'list_id' => 'required',
            'judul' => 'required',
            'file_sop' => 'file|mimes:pdf|max:1024',
            'file_permohonan' => 'file|mimes:pdf|max:1024',
        ], [
            'list_id.required' => 'Kategori Pelayanan wajib dipilih.',
            'judul.required' => 'Judul wajib diisi.',
            'file_sop.mimes' => 'File SOP harus berupa PDF.',
            'file_sop.max' => 'File SOP maksimal 1 MB.',
            'file_permohonan.mimes' => 'File Permohonan harus berupa PDF.',
            'file_permohonan.max' => 'File Permohonan maksimal 1 MB.',
        ]);
        if ($request->hasFile('file_sop')) {
            $data['file_sop'] = $request->file('file_sop')->store('file/sop');
        }
        if ($request->hasFile('file_permohonan')) {
            $data['file_permohonan'] = $request->file('file_permohonan')->store('file/permohonan');
        }
        $this->service->create([
            'list_id' => $data['list_id'],
            'judul' => ucwords($data['judul']),
            'file_sop' => $data['file_sop'] ?? null,
            'file_permohonan' => $data['file_permohonan'] ?? null,
        ]);
        $message = $this->generateSuccessMessage('Data Pelayanan berhasil dibuat.');
        return redirect()->route('service')->with('message', $message);
    }

    public function deleteService($id)
    {
        $service = $this->service->findOrFail($id);
        if ($service->file_sop) {
            unlink(storage_path('app/public/' . $service->file_sop));
        }
        if ($service->file_permohonan) {
            unlink(storage_path('app/public/' . $service->file_permohonan));
        }

        $service->delete();
        $message = $this->generateSuccessMessage('Data Pelayanan berhasil dihapus.');
        return redirect()->route('service')->with('message', $message);
    }

    public function updateService(Request $request, $id)
    {
        $data = $request->validate([
            'list_id' => 'required',
            'judul' => 'required',
            'file_sop' => 'file|mimes:pdf|max:1024',
            'file_permohonan' => 'file|mimes:pdf|max:1024',
        ], [
            'list_id.required' => 'Kategori Pelayanan wajib dipilih.',
            'judul.required' => 'Judul wajib diisi.',
            'file_sop.mimes' => 'File SOP harus berupa PDF.',
            'file_sop.max' => 'File SOP maksimal 1 MB.',
            'file_permohonan.mimes' => 'File Permohonan harus berupa PDF.',
            'file_permohonan.max' => 'File Permohonan maksimal 1 MB.',
        ]);
        $service = $this->service->findOrFail($id);
        if ($request->hasFile('file_sop')) {
            if ($service->file_sop) {
                unlink(storage_path('app/public/' . $service->file_sop));
            }
            $data['file_sop'] = $request->file('file_sop')->store('file/sop');
            $service->update([
                'list_id' => $data['list_id'],
                'judul' => ucwords($data['judul']),
                'file_sop' => $data['file_sop'],
            ]);
        }
        if ($request->hasFile('file_permohonan')) {
            if ($service->file_permohonan) {
                unlink(storage_path('app/public/' . $service->file_permohonan));
            }
            $data['file_permohonan'] = $request->file('file_permohonan')->store('file/permohonan');
            $service->update([
                'list_id' => $data['list_id'],
                'judul' => ucwords($data['judul']),
                'file_permohonan' => $data['file_permohonan'],
            ]);
        }
        $service->update([
            'list_id' => $data['list_id'],
            'judul' => ucwords($data['judul']),
        ]);
        $message = $this->generateSuccessMessage('Data Pelayanan berhasil diupdate.');
        return redirect()->route('service')->with('message', $message);
    }

    public function editService($id)
    {
        return view('cms.page.services.service.edit', [
            'service' => $this->service->findOrFail($id),
            'listServices' => $this->listService->latest()->get(),
        ]);
    }

    public function inbox()
    {
        return view('cms.page.services.inbox.index', [
            'inboxes' => $this->serviceApplicant->with('list', 'service')->latest()->get(),
        ]);
    }

    public function deleteInbox($id)
    {
        $inbox = $this->serviceApplicant->findOrFail($id);
        if ($inbox->file_persyaratan) {
            unlink(storage_path('app/public/' . $inbox->file_persyaratan));
        }
        $inbox->delete();
        $message = $this->generateSuccessMessage('Kotak Masuk berhasil dihapus.');
        return redirect()->route('inbox')->with('message', $message);
    }

    public function updateApplicant(Request $request)
    {
        $request->validate([
            'kode_layanan' => 'required|string',
            'diproses_oleh' => 'nullable|string',
            'pesan_balasan' => 'nullable|string',
            'status' => 'required|in:pending,diproses,selesai',
        ]);

        $serviceApplicant = $this->serviceApplicant->where('kode_layanan', $request->kode_layanan)->first();
        if ($serviceApplicant) {
            $serviceApplicant->update([
                'diproses_oleh' => $request->diproses_oleh,
                'pesan_balasan' => $request->pesan_balasan,
                'status' => $request->status,
            ]);
            return response()->json(['success' => 'Data berhasil diperbarui']);
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function sendStatusEmail(Request $request)
    {
        $kodeLayanan = $request->input('kode_layanan');
        $applicant = $this->serviceApplicant->with(['list', 'service'])->where('kode_layanan', $kodeLayanan)->first();
        if ($applicant) {
            Mail::to($applicant->email)->send(new ApplicantStatusMail($applicant));
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    public function serviceIn()
    {
        return view('cms.page.report.view.service-in', [
            'inboxes' => $this->serviceApplicant->with('list', 'service')->latest()->get(),
        ]);
    }

    public function serviceByCategory()
    {
        return view('cms.page.report.view.service-category', [
            'listServices' => $this->listService->with('services', 'applicants')->get(),
        ]);
    }

    public function serviceStatuses()
    {
        return view('cms.page.report.view.service-status', [
            'services' => $this->serviceApplicant->with('list', 'service')->latest()->get(),
        ]);
    }

    public function comment()
    {
        return view('cms.page.comment.index', [
            'comments' => $this->comment->with('media')->where('spam', 1)->latest()->get(),
        ]);
    }

    public function deleteComment($id)
    {
        $comment = $this->comment->findOrFail($id);
        $comment->delete();
        $message = $this->generateSuccessMessage('Komentar berhasil dihapus.');
        return redirect()->route('comment')->with('message', $message);
    }

    public function refuseComment($id)
    {
        $comment = $this->comment->find($id);
        $comment->update(['spam' => 0]);
        return back()->with('message', $this->generateSuccessMessage('Komentar berhasil diabaikan.'));
    }

    public function feedback()
    {
        return view('cms.page.feedback.index', [
            'feedbacks' => $this->feedback->latest()->get(),
        ]);
    }

    public function feedbackDelete($id)
    {
        $feedback = $this->feedback->findOrFail($id);
        $feedback->delete();
        $message = $this->generateSuccessMessage('Feedback berhasil dihapus.');
        return redirect()->route('feedback.show')->with('message', $message);
    }

    // report
    public function allMedia()
    {
        return view('cms.page.report.view.all-media', [
            'medias' => $this->media->with('user', 'category', 'tags')
                ->latest()
                ->get(),
        ]);
    }

    public function mediaByCategory(Request $request)
    {
        if ($request->has('categoryID')) {
            $inputCategory = $request->get('categoryID');
            $category = $this->category->findOrFail($inputCategory);
            $medias = $category->medias()->orderByDesc('id')->get();
            $categories = $this->category->all();
            return view('cms.page.report.view.media-by-category', [
                'medias' => $medias,
                'categories' => $categories,
                'inputCategory' => $inputCategory,
            ]);
        } else {
            $medias = $this->media->with('user', 'category', 'tags')->latest()->get();
            $categories = $this->category->all();
            $inputCategory = null;
            return view('cms.page.report.view.media-by-category', [
                'medias' => $medias,
                'categories' => $categories,
                'inputCategory' => $inputCategory,
            ]);
        }
    }

    public function mediaByTime(Request $request)
    {
        if ($request->has('start') && $request->has('end')) {
            $start = Carbon::parse($request->get('start'));
            $end = Carbon::parse($request->get('end'))->endOfDay();

            $medias = $this->media->whereBetween('created_at', [$start, $end])
                ->orderBy('created_at', 'ASC')
                ->get();

            return view('cms.page.report.view.media-by-time', compact('medias', 'start', 'end'));
        } else {
            $medias = null;
            $start = null;
            $end = null;
            return view('cms.page.report.view.media-by-time', compact('medias', 'start', 'end'));
        }
    }

    public function popularMedia()
    {
        return view('cms.page.report.view.popular-media', [
            'medias' => $this->media->where('jumlah_dibaca', '>=', 2)->orderByDesc('jumlah_dibaca')->get(),
        ]);
    }

    public function mostMediaCategory()
    {
        return view('cms.page.report.view.most-media-category', [
            'categories' => $this->category->withCount('medias')->orderBy('id', 'DESC')->get(),
        ]);
    }
}
