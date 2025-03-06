<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;

class ClientMemberController extends Controller
{
    function index (){
        $createdShortUrls = ShortUrl::where('user_id', auth()->user()->id)->paginate(1);
        return view('dashboards.client-member.dashboard', compact('createdShortUrls'));
    }

    public function form(){
        return view('dashboards.client-member.url-shortner-form');
    }
}
