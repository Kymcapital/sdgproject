<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FAQAdminController extends Controller
{
    public function faqAdmin(){
        return view('backend.faq.index');
    }
}
