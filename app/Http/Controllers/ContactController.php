<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Contact;
use App\Models\Survey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\contactus;
use App\Http\Requests\SurveyRequest;

class ContactController extends Controller
{

    public function index() {

        return view('contact.index');
    }

    public function store(contactus $request) {


        $gg = $request->validated();



        $insert = Contact::Create($gg);

        if($insert->save()) {

            
            $request->session()->flash('flash.banner', "Message Sent! We'll be in touch :)");
            return back();

        } else {
            $request->session()->flash('flash.banner', 'Error!');
            $request->session()->flash('flash.bannerStyle', 'danger');
            return back();
        }
        //dd($gg);

        return view('contact.index');
    }

    public function viewSurvey() {

        $completed = Survey::Where('user_id', Auth::user()->id)->exists();
        return view('contact.survey', ['status' => $completed]);
    }

    public function storeSurvey(SurveyRequest $request) {


        $gg = $request->validated();



        $insert = Survey::Create($gg);

        if($insert->save()) {

            
            $request->session()->flash('flash.banner', "Survey Submitted! Thanks for playing!");
            return back();

        } else {
            $request->session()->flash('flash.banner', 'Error!');
            $request->session()->flash('flash.bannerStyle', 'danger');
            return back();
        }


        return view('contact.survey');
    }
    
}