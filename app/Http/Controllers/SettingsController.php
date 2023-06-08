<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getNgrockUri() 
    {
        $url = 'http://localhost:3000/api/tunnels';

        $ch = curl_init($url);
        $result = curl_exec($ch);
        curl_close($ch);
dd(curl_error($ch));
        return $result;
    }
}
