<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generateLicense()
    {
        // Load the 8x13 marriage license view.
        $pdf = Pdf::loadView('pdf.8x13');

        // 2. Set the paper size to Legal (8.5in x 14in)
        // This is crucial so your absolute positioning (px) stays aligned
        $pdf->setPaper('legal', 'portrait');

        // 3. Download the file
        return $pdf->download('Marriage_License.pdf');
    }
}
