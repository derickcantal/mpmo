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

        // Example action: log or store the scanned text
        \Log::info('Scanned QR Text:', ['text' => $scannedText]);

        return response()->json([
            'status' => 'success',
            'message' => 'Received: ' . $scannedText,
        ]);
    }
}
