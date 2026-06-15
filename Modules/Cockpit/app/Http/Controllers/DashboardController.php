<?php

namespace Modules\Cockpit\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Cockpit\app\Services\PreferenceManager;

class DashboardController extends Controller
{
    public function __construct(private PreferenceManager $preferences) {}

    public function index()
    {
        $prefs = $this->preferences->all('global');

        return view('cockpit::dashboard.index', compact('prefs'));
    }
}
