<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{

    public function contactus() {

        return view('contact.index');
    }

    public function winner() {

        return view('livewire.survivor_winner');
    }
    
    public function index() {
/*
         if (Auth::guest()) {
        $user = User::Find(14);
        auth()->login($user, true);
        }
*/
        return view('home.index');
    }

    public function testSesh(Request $request) {
        
        //dd($request->cookie('ref'));
        /*
        if (Auth::guest()) {
        $user = User::Find(44);
        auth()->login($user, true);
        }
        
        /*
         if (request()->cookie('ref')) {
            dd(true);
        } else {
            dd(false);
        }
        */
        
    }

    public function referalInfo() {

        $CacheKey = Auth::user()->getReferrals()->first()->code;
        foreach(Auth::user()->getReferrals() as $referral)
        {
        $arr[] =  $referral->link;
        }
        if (Cache::has($CacheKey)) {
        $status = true;
        } else {
        $status = false;
        }

        if($status) {
        $cart = Cache::get($CacheKey);
        } else {
        $response = Http::m3o()->post('/qr/Generate', [
        'size' => 300,
        'text' => Auth::user()->getReferrals()->first()->link,
        ]);
        $r = json_decode($response);  
        $r->qr;
        if($r->qr) {
        Cache::forever($CacheKey, $r->qr);
        $cart = Cache::get($CacheKey);
        }
        }

        return view('my-referal', ['qr' => $cart, 'referals' => Auth::user()->getReferrals()->first()->relationships()->count(), 'relate' => Auth::user()->getReferrals()->first()->relationships()->get(), 'reflink' => Auth::user()->getReferrals()->first()->link]);

    }
}
