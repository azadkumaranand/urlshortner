<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\GenratedShortUrlExportSheet;

class GenratedShortUrlExport implements WithMultipleSheets
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
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new GenratedShortUrlExportSheet($this->reportData, $this->heading);
        return $sheets;
    }
}
