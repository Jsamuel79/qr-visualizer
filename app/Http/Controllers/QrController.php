<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

// Endroid QR Code v6+
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\RoundBlockSizeMode;

class QrController extends Controller
{
    public function index()
    {
        return view('qr.form');
    }

    public function generateQr(Request $request)
    {
        // Récupérer les données du formulaire
        $data = $request->input('sites', []);
        if (empty($data)) {
            abort(400, 'Aucune donnée reçue pour le QR code');
        }

        // Convertir le tableau en JSON pour le QR
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        // ⚡ Création du QR code avec Builder (v6)
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $jsonData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        $result = $builder->build();

        // Encode en base64 pour l’injecter dans le PDF
        $qrBase64 = base64_encode($result->getString());
        $qrSrc = 'data:image/png;base64,' . $qrBase64;

        // Génération du PDF avec la vue qr.pdf
        $pdf = Pdf::loadView('qr.pdf', ['qrSrc' => $qrSrc]);

        // Téléchargement direct du PDF
        return $pdf->download('qr_code.pdf');
    }

    public function store(Request $request)
    {
        $data = $request->input('qrdata');

        $decoded = json_decode($data, true);
        if (!$decoded) {
            return response()->json(['error' => 'QR invalide'], 400);
        }

        // Sauvegarde dans la BDD
        DB::table('inventaire')->insert([
            'structure' => json_encode($decoded),
            'created_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}

