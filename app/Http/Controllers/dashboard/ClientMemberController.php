<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use App\Exports\GenratedShortUrlExport;

class ClientMemberController extends Controller
{
    function index (Request $request){
        $urlQuery = $createdShortUrls = ShortUrl::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc');
        if($request->query('q') && !is_null($request->query('q'))){
            if($request->query('q') == 'tm'){
                $urlQuery->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            }elseif($request->query('q') == 'lm'){
                $urlQuery->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
            }elseif($request->query('q') == 'lw'){
                $urlQuery->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
            }elseif($request->query('q') == 'today'){
                $urlQuery->whereDate('created_at', now()->today());
            }
        }
        $createdShortUrls = $urlQuery->paginate(3, ['*'], 'url_page');
        return view('dashboards.client-member.dashboard', compact('createdShortUrls'));
    }

    public function downloadReport(){
        $shortUrl = ShortUrl::where('user_id', auth()->user()->id)->get()->map(function($url){
            return [
                'id' => $url->id,
                "long_url"=>$url->original_url,
                'short_url'=>url($url->short_url),
                'hits'=>$url->count,
                'created_at'=>date('d M y', strtotime($url->created_at))
            ];
        });
        $heading = [
            'id',
            'Long Url',
            'Short Url',
            'Hits',
            'Created On'
        ];
        return \Excel::download(new GenratedShortUrlExport($shortUrl, $heading), 'short-url-report.xlsx');
    }

    public function form(){
        return view('dashboards.client-member.url-shortner-form');
    }
}
