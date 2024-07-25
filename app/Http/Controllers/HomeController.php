<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Encryption;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    use Encryption;
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->middleware('auth');
        return view('home');
    }
    public function generateLink($id)
    {
        $id = $this->encryptId($id);
        echo url('/?ref='.$id);
    }
}
