<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Category;
use App\Models\ListService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ServiceApplicant;

class ReportController extends Controller
{
    protected $media, $category, $serviceApplicant, $listService;

    public function __construct(Media $media, Category $category, ServiceApplicant $serviceApplicant, ListService $listService)
    {
        $this->media = $media;
        $this->category = $category;
        $this->serviceApplicant = $serviceApplicant;
        $this->listService = $listService;
    }
    public function allPostOutput()
    {
        $pdf = Pdf::loadView('cms.page.report.output.all-media', [
            'medias' => $this->media->with('user', 'category', 'tags')
                ->latest()
                ->get(),
        ]);
        return $pdf->download('semua-media.pdf');
    }

    public function mediaByCategoryOutput(Request $request)
    {
        if ($request->has('categoryID')) {
            $inputCategory = $request->get('categoryID');
            $pdf = Pdf::loadView('cms.page.report.output.media-by-category', [
                'medias' => $this->media->with('user', 'category', 'tags')
                    ->where('category_id', $inputCategory)
                    ->latest()
                    ->get(),
            ]);
            return $pdf->download('media-dari-kategori.pdf');
        }
    }

    public function mediaByTimeOutput(Request $request)
    {
        if ($request->has('start') && $request->has('end')) {
            $start = Carbon::parse($request->get('start'));
            $end = Carbon::parse($request->get('end'))->endOfDay();

            $pdf = Pdf::loadView('cms.page.report.output.media-by-time', [
                'medias' => $this->media->whereBetween('created_at', [$start, $end])
                    ->orderBy('created_at', 'ASC')
                    ->get(),
            ]);
            return $pdf->download('media-berdasarkan-waktu.pdf');
        }
    }

    public function popularPostOutput()
    {
        $pdf = Pdf::loadView('cms.page.report.output.popular-media', [
            'medias' => $this->media->where('jumlah_dibaca', '>=', 2)->orderByDesc('jumlah_dibaca')->get(),
        ]);
        return $pdf->download('media-populer.pdf');
    }

    public function mostMediaCategoryOutput()
    {
        $pdf = Pdf::loadView('cms.page.report.output.most-media-category', [
            'categories' => $this->category->withCount('medias')->orderBy('id', 'DESC')->get(),
        ]);
        return $pdf->download('kategori-media-terbanyak.pdf');
    }

    public function serviceInOutput()
    {
        $pdf = Pdf::loadView('cms.page.report.output.service-in', [
            'serviceApplicants' => $this->serviceApplicant->with('list', 'service')->latest()->get(),
        ]);
        return $pdf->download('pelayanan-masuk.pdf');
    }

    public function serviceByCategoryOutput()
    {
        $pdf = Pdf::loadView('cms.page.report.output.service-category', [
            'listServices' => $this->listService->with('services', 'applicants')->get(),
        ]);
        return $pdf->download('pelayanan-berdasarkan-kategori.pdf');
    }

    public function serviceStatusesOutput()
    {
        $pdf = Pdf::loadView('cms.page.report.output.service-status', [
            'services' => $this->serviceApplicant->with('list', 'service')->latest()->get(),
        ])->setPaper('a4', 'landscape');
        return $pdf->download('status-pelayanan.pdf');
    }
}
