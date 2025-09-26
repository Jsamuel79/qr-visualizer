<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class QrController extends Controller
{
    public function index()
    {
        return view('qr.form');
    }

    public function generateQr(Request $request)
    {
        $data = $request->input('data');

        // Création du QR code
        $qrCode = QrCode::create($data)->setSize(300);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Encode en base64 pour l'injecter dans le PDF
        $qrBase64 = base64_encode($result->getString());
        $qrSrc = 'data:image/png;base64,' . $qrBase64;

        // Génération du PDF
        $pdf = Pdf::loadView('qr.pdf', ['qrSrc' => $qrSrc]);

        return $pdf->download('qr_code.pdf');
    }
}
