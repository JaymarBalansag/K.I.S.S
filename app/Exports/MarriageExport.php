<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class MarriageExport implements WithEvents
{
    use RegistersEventListeners;

    protected $groom;
    protected $bride;

    public function __construct($groom, $bride)
    {
        $this->groom = $groom;
        $this->bride = $bride;
    }
    

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $templatePath = storage_path('app/private/templates/application for marriage.xlsx');
                // Check if it exists before trying to use it
                if (!file_exists($templatePath)) {
                    throw new \Exception("Template file not found at: " . $templatePath);
                }

                $sheet = $event->sheet->getDelegate();

                // Groom Data
                $sheet->setCellValue('C12', $this->groom->first_name . ' ' . $this->groom->last_name);
                $sheet->setCellValue('C18', $this->groom->first_name);
                $sheet->setCellValue('C19', $this->groom->middle_name ?: " ");
                $sheet->setCellValue('C20', $this->groom->last_name);
                $sheet->setCellValue('B22', $this->groom->day);
                $sheet->setCellValue('D22', $this->groom->month);
                $sheet->setCellValue('H22', $this->groom->year);
                $sheet->setCellValue('J22', $this->groom->age);
                $sheet->setCellValue('B23', $this->groom->birth_city . " " . $this->groom->birth_province . " " . $this->groom->birth_country);
                $sheet->setCellValue('B24', $this->groom->sex);
                $sheet->setCellValue('G24', $this->groom->citizenship);
                $sheet->setCellValue('B25', $this->groom->residence_address);
                $sheet->setCellValue('B26', $this->groom->religion);
                $sheet->setCellValue('B27', $this->groom->civil_status);
                $sheet->setCellValue('B28', $this->groom->dissolution_details ?: "NOT APPLICABLE");
                $sheet->setCellValue('B30', $this->groom->dissolution_place ?: "NOT APPPLICABLE");
                $sheet->setCellValue('B32', ($this->groom->dissolution_day . " " . $this->groom->dissolution_month . " " . $this->groom->dissolution_year) ?: "NOT APPLICABLE");
                $sheet->setCellValue('B33', $this->groom->relationship_degree);
                $sheet->setCellValue('B35', $this->groom->father_first_name . " " . ($this->groom->father_middle_name ?? "") . " " . $this->groom->father_last_name);                $sheet->setCellValue('B36', $this->groom->father_citizenship);
                $sheet->setCellValue('B38', $this->groom->father_residence);
                $sheet->setCellValue('B39', $this->groom->mother_first_name . " " . ($this->groom->mother_middle_name ?? "") . " " . $this->groom->mother_last_name);                $sheet->setCellValue('B40', $this->groom->mother_citizenship);
                $sheet->setCellValue('B42', $this->groom->mother_residence);
                $sheet->setCellValue('B43', "NOT APPLICABLE");
                $sheet->setCellValue('B44', "NOT APPLICABLE");
                $sheet->setCellValue('B45', "NOT APPLICABLE");
                $sheet->setCellValue('B46', "NOT APPLICABLE");
                
            },
        ];
    }
}