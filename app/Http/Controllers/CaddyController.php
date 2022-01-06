<?php
# app/Http/Controllers/CaddyController.php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;

class CaddyController extends Controller
{
    public function check(Request $request)
    {
        $authorizedDomains = [
            'vtms.online',
            'www.vtms.online',
            // Add subdomains here
        ];

        if (in_array($request->query('domain'), $authorizedDomains)) {
            return response('Domain Authorized');
        }

        // Abort if there's no 200 response returned above
        abort(503);
    }
}
