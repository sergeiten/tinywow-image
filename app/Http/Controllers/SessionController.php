<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class SessionController extends Controller
{
    public function get()
    {
        while (true) {
            $sessionID = Str::random(18);

            if (!file_exists(storage_path('app/public/' . $sessionID))) {
                return response()->json(['session_id' => $sessionID]);
            }
        }
    }
}
