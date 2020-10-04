<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{



    public function getSession(Request $request)
    {
        if (isset($_SESSION['game'])) {
            return  $_SESSION['game'];
        }
    }
}
