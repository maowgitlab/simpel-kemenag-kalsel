<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Comment;
use App\Models\Service;
use App\Models\Category;
use App\Mail\ApplicantMail;
use App\Models\ListService;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use App\Models\ServiceApplicant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;

class BlogController extends Controller
{
    protected $trendingCategories;
    protected $categories;
    protected $medias;
    protected $trendingMedias;
    protected $latestMedias;
    protected $choicesMedias;

    public function __construct()
    {
        $this->trendingCategories = Category::with([
            'medias' => fn($query) =>
            $query->latest()->take(10)
        ])
            ->withCount('medias')
            ->having('medias_count', '>=', 10)
            ->orderByDesc('medias_count')
            ->take(3)
            ->get();
        $this->categories = Category::orderBy('jumlah_dibaca', 'DESC')->get();
        $this->medias = Media::with('category', 'user', 'tags', 'comments')->get();
        $this->trendingMedias = $this->medias->sortByDesc('jumlah_dibaca');
        $this->latestMedias = $this->medias->sortByDesc('created_at');
        $this->choicesMedias = $this->medias->where('pilihan', true)->sortByDesc('created_at')->take(5);
    }

    public function home()
    {
        if (Auth::check()) {
            return back();
        }
        return view('blog.page.home', [
            'trendingCategories' => $this->trendingCategories,
            'categories' => $this->categories,
            'latestMedias' => $this->latestMedias,
            'trendingMedias' => $this->trendingMedias,
            'choicesMedias' => $this->choicesMedias,
        ]);
    }

    public function mediaShow($slug)
    {
        if (Auth::check()) {
            return back();
        }
        if (!session()->has('media_viewed_' . $slug)) {
            $this->medias->where('slug', $slug)->first()->increment('jumlah_dibaca');
            session(['media_viewed_' . $slug => true]);
        }
        return view('blog.page.media', [
            'media' => $this->medias->where('slug', $slug)->first() ?? abort(404),
            'categories' => $this->categories,
            'latestMedias' => $this->latestMedias,
            'trendingMedias' => $this->trendingMedias,
        ]);
    }

    public function mediaSearch($keyword)
    {
        if (Auth::check()) {
            return back();
        }
        return view('blog.page.media', [
            'keyword' => $keyword,
            'medias' => Media::where('judul', 'like', '%' . $keyword . '%')->paginate(5)->withQueryString(),
            'categories' => $this->categories,
            'latestMedias' => $this->latestMedias,
            'trendingMedias' => $this->trendingMedias,
        ]);
    }

    public function mediaCategory($slug)
    {
        if (Auth::check()) {
            return back();
        }
        $category = $this->categories->where('slug', $slug)->first() ?? abort(404);
        if (!session()->has('category_viewed_' . $category->id)) {
            $category->increment('jumlah_dibaca');
            session(['category_viewed_' . $category->id => true]);
        }
        $medias = Media::where('category_id', $category->id)->paginate(5)->withQueryString();
        return view('blog.page.media', [
            'category' => $category,
            'medias' => $medias,
            'categories' => $this->categories,
            'latestMedias' => $this->latestMedias,
            'trendingMedias' => $this->trendingMedias,
        ]);
    }

    public function commentSend(Request $request)
    {
        $data = $request->validate([
            'media_id' => 'required',
            'perangkat' => 'required',
            'komentar' => 'required',
            // 'g-recaptcha-response' => 'required|captcha',
        ], [
            'komentar.required' => 'Komentar wajib diisi',
            // 'g-recaptcha-response.required' => 'Captcha dibutuhkan.',
            // 'g-recaptcha-response.captcha' => 'Validasi Captcha Gagal, Silahkan coba lagi.',
        ]);
        Comment::create($data);
        return back()->with('message', 'Komentar berhasil diposting.');
    }

    public function commentReport($id)
    {
        $comment = Comment::find($id);
        $comment->update(['spam' => 1]);
        return back()->with('message', 'Komentar berhail dilaporkan.');
    }

    public function commentUniqueID()
    {
        $uniqueID = Cookie::get('uniqueID');
        if (!$uniqueID) {
            $uniqueID = strtoupper(bin2hex(random_bytes(3))); // Generate 6-character unique ID
            Cookie::queue('uniqueID', $uniqueID, 60 * 24 * 365); // Cookie untuk 1 tahun
        }
        return response()->json(['uniqueID' => $uniqueID]);
    }

    public function serviceShow()
    {
        if (Auth::check()) {
            return back();
        }
        return view('blog.page.media', [
            'listServices' => ListService::all(),
            'categories' => $this->categories,
            'latestMedias' => $this->latestMedias,
            'trendingMedias' => $this->trendingMedias,
        ]);
    }

    public function serviceStatus(Request $request)
    {
        $data = $request->validate([
            'email_status' => 'required|email',
        ], [
            'email_status.required' => 'Email wajib diisi',
            'email_status.email' => 'Email tidak valid',
        ]);

        $serviceApplicants = ServiceApplicant::where('email', $data['email_status'])->latest()->get();

        if ($serviceApplicants->isEmpty()) {
            return redirect()->back()->with('error_message', 'Data yang Anda masukan tidak ditemukan.');
        }

        return redirect()->back()->with('serviceApplicants', $serviceApplicants);
    }

    public function getServicesByCategory($id)
    {
        $services = Service::where('list_id', $id)->get();
        return response()->json($services);
    }

    public function getServiceDetail($id)
    {
        $service = Service::find($id);
        return response()->json($service);
    }

    public function createNewApplicant(Request $request)
    {
        $isFilePersyaratanRequired = $request->post('is_file_persyaratan_required');
        $request->validate([
            'kategori_layanan' => 'required',
            'email' => 'required|email',
            'layanan' => 'required',
            'nama' => 'required',
            'pesan' => 'required',
            'file_persyaratan' => $isFilePersyaratanRequired == 1 ? 'required|mimes:pdf|max:1024' : '',
        ], [
            'kategori_layanan.required' => 'Kategori Layanan wajib dipilih',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'layanan.required' => 'Layanan wajib dipilih',
            'nama.required' => 'Nama wajib diisi',
            'pesan.required' => 'Pesan wajib diisi',
            'file_persyaratan.required' => 'File persyaratan wajib diisi',
            'file_persyaratan.mimes' => 'File persyaratan harus berupa PDF',
            'file_persyaratan.max' => 'File persyaratan maksimal 1 MB',
        ]);
        $faker = Faker::create();
        if ($request->hasFile('file_persyaratan')) {
            $data = ServiceApplicant::create([
                'kode_layanan' => $faker->unique->numerify('PLYN#####'),
                'list_id' => $request->post('kategori_layanan'),
                'service_id' => $request->post('layanan'),
                'nama' => $request->post('nama'),
                'email' => $request->post('email'),
                'pesan_pengirim' => $request->post('pesan'),
                'status' => 'pending',
                'file_persyaratan' => $request->file('file_persyaratan')->store('file/persyaratan'),
            ]);
            Mail::to($data->email)->send(new ApplicantMail($data));
            return redirect()->back()->with('message', 'Permohonan berhasil dibuat. Silahkan Periksa Status Secara berkala menggunakan Email.');
        }
        $data = ServiceApplicant::create([
            'kode_layanan' => $faker->unique->numerify('PLYN#####'),
            'list_id' => $request->post('kategori_layanan'),
            'service_id' => $request->post('layanan'),
            'nama' => $request->post('nama'),
            'email' => $request->post('email'),
            'pesan_pengirim' => $request->post('pesan'),
            'status' => 'pending',
        ]);
        $data->list->increment('jumlah_pengajuan');
        Mail::to($data->email)->send(new ApplicantMail($data));
        return redirect()->back()->with('message', 'Permohonan berhasil dibuat. Silahkan Periksa Status Secara berkala menggunakan Email.');
    }

    public function detailApplicant(Request $request)
    {
        $kodeLayanan = $request->query('kode_layanan');
        $serviceApplicant = ServiceApplicant::with(['list', 'service'])->where('kode_layanan', $kodeLayanan)->first();

        if ($serviceApplicant) {
            return response()->json($serviceApplicant);
        }
        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    public function updateRating(Request $request)
    {
        $serviceApplicant = ServiceApplicant::where('kode_layanan', $request->kode_layanan)->first();

        if ($serviceApplicant) {
            $serviceApplicant->update(['rating' => $request->rating]);
            return response()->json(['success' => 'Rating berhasil diperbarui']);
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function feedback(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'email' => 'required|email',
        ]);

        \App\Models\Feedback::create([
            'email' => $validated['email'],
            'tipe_feedback' => $request->post('feedbackType'),
            'url' => $request->post('url'),
            'pesan' => $validated['message'],
        ]);

        return response()->json(['message' => 'Feedback submitted successfully.'], 200);
    }
}
