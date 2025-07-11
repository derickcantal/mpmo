<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrController extends Controller
{
    public function scan()
    {
        return view('scan');
    }

    public function submit(Request $request)
    {
        $scannedText = $request->input('scanned_text');

        // Handle the scanned text, e.g., search in DB, log, etc.
        // Example: return as a response
        return back()->with('success', 'Scanned QR Text: ' . $scannedText);
    }
}
