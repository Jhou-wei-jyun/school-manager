<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QrcodeController extends Controller
{
    public function makeQrcode(Request $request)
    {
        $qrcode = QrCode::format('png')->size(500)->encoding('UTF-8')->generate('11111');
        // $filenameWithExt    = $qrcode->getClientOriginalName();
        // dd($filenameWithExt);
        // $filename           = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // $extension          = strtolower($file->getClientOriginalExtension());
        // $filename = $this->ASCIIFilter($filename);
        // $filename = $this->SpaceTo_($filename);
        // $fileNameToStore    = $filename . '.' . $extension;
        Storage::put('qrcode' . Str::random(32) . '.png', $qrcode);
        return view('qr-code');
    }
    public function check(Request $request)
    {
        return '1';
    }
}
