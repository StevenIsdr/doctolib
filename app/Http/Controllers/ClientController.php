<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        if (Auth::user()->isDoc()) {
            return view('doc.doc');
        } else {
            return view('client.client');
        }
    }

    public function horaires()
    {
        return view('doc.horaires');
    }
}
