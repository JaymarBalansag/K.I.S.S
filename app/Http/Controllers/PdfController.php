<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generateLicense()
    {
        // 1. Load your trial.blade.php file
        $pdf = Pdf::loadView('pdf.trial');

        // 2. Set the paper size to Legal (8.5in x 14in) 
        // This is crucial so your absolute positioning (px) stays aligned
        $pdf->setPaper('legal', 'portrait');

        // 3. Download the file
        return $pdf->download('Marriage_License.pdf');
    }
}
