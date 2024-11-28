<?php

namespace App\Services\Util;

use Spatie\SimpleExcel\SimpleExcelWriter;
/**
 * Class ExcelService
 * @package App\Services
 */
class ExcelService
{
    private $row;
    
    public function Excel($type, $lists, $totCnt)
    {
            
        $excel = SimpleExcelWriter::streamDownload('Excel.csv');
        $excel->noHeaderRow();

        // Header
        $header = [
            'No',
        ];

        // Add header to the CSV
        $excel->addRow($header);

        // Add data to the CSV
        foreach ($lists->lazy(3000) as $key => $row) {
            $this->row[$key] = [
                ($totCnt - $key),
            ];

            // 특수문자 때문에
            $this->row[$key] = excelEntity($this->row[$key]);

            $excel->addRow($this->row[$key]);
        }

        // Download the CSV
        return $excel->toBrowser();
    }
}
