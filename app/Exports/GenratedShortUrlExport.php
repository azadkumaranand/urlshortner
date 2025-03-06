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
    public function __construct($reportData){
        $this->reportData = $reportData;
    }
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new GenratedShortUrlExportSheet($this->reportData);
        return $sheets;
    }
}
