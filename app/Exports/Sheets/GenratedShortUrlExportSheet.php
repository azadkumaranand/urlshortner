<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenratedShortUrlExportSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $reportData;
    public $heading;
    public function __construct($reportData, $heading){
        $this->reportData = $reportData;
        $this->heading = $heading;
        
    }
    public function collection()
    {
        return $this->reportData; 
    }
    public function headings(): array
    {
        return $this->heading;
    }
    public function title(): string
    {
        return 'Generated Urls';
    }
}
