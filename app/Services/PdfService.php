<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function generateHolidayPlanPdf($holidayPlan)
    {
        $pdf = Pdf::loadView('pdf.holiday_plan', ['holidayPlan' => $holidayPlan]);

        // Return the PDF as a download
        return $pdf->download('holiday_plan.pdf');
    }
}
