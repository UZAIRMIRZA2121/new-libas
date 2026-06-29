<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTracking;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function store(Request $request)
    {
        // Tracking disabled temporarily as requested
        return response()->json(['status' => 'success']);
        
        $validated = $request->validate([
            'session_id' => 'required|string',
            'event_type' => 'required|string',
            'url' => 'required|string',
            'element_text' => 'nullable|string',
            'email' => 'nullable|email'
        ]);

        $tracking = new UserTracking();
        $tracking->session_id = $validated['session_id'];
        $tracking->event_type = $validated['event_type'];
        $tracking->url = $validated['url'];
        $tracking->element_text = $validated['element_text'] ?? null;
        
        // Handle User Identification
        if (Auth::check()) {
            $tracking->user_id = Auth::id();
        }
        
        // If email is provided (from popup), save it. Also, optionally update past session events with this email
        if (!empty($validated['email'])) {
            $tracking->email = $validated['email'];
            
            // Retroactively tag previous anonymous session activities with this email
            UserTracking::where('session_id', $validated['session_id'])
                        ->whereNull('email')
                        ->update(['email' => $validated['email']]);
        } else {
            // Check if we already have an email for this session_id from previous events
            $existingEmail = UserTracking::where('session_id', $validated['session_id'])
                                         ->whereNotNull('email')
                                         ->value('email');
            if ($existingEmail) {
                $tracking->email = $existingEmail;
            }
        }

        $tracking->save();

        return response()->json(['status' => 'success']);
    }
}
