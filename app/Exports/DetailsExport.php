<?php

namespace App\Exports;

use App\Models\Detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DetailsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Detail::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Vehicle Number',
            'Party Name',
            'First Weight',
            'Second Weight',
            'Net Weight',
            'First Date',
            'Second Date',
            'Amount',
            'Description',
            'Created By',
            'Created At',
            'Updated At',
        ];
    }
}
