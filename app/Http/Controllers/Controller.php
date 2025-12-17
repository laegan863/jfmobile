<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function apiUrl($url)
    {
        return "https://prodapitmo.321communications.com/api/tmo/v1/".$url;
    }
}
