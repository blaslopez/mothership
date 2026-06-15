<?php

namespace Modules\Cockpit\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cockpit\app\Services\PreferenceManager;

class PreferenceController extends Controller
{
    public function __construct(private PreferenceManager $preferences) {}

    public function show()
    {
        $prefs = $this->preferences->all('global');

        return view('cockpit::preferences.show', compact('prefs'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'theme'  => ['sometimes', 'string', 'in:light,dark'],
            'layout' => ['sometimes', 'string', 'in:default,compact'],
            'locale' => ['sometimes', 'string', 'in:en,ca'],
        ]);

        foreach ($data as $key => $value) {
            $this->preferences->set('global', $key, $value);
        }

        return back()->with('success', __('cockpit::preferences.saved'));
    }

    /**
     * Sync guest preferences from localStorage to DB after login.
     */
    public function sync(Request $request)
    {
        $data = $request->validate([
            'preferences' => ['required', 'array'],
        ]);

        $this->preferences->syncFromClient($data['preferences']);

        return response()->json(['status' => 'ok']);
    }
}
