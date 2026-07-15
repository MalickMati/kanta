<?php

namespace App\Exports;

use App\Models\Detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DetailsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Detail::all();
    }

    public function map($detail): array
    {
        return [
            $detail->id,
            $detail->vehicle_number,
            $detail->party,
            $detail->first_weight,
            $detail->second_weight,
            $detail->net_weight,
            $detail->first_date,
            $detail->second_date,
            $detail->amount,
            $detail->description,
            $detail->created_by,
            $detail->created_at,
            $detail->updated_at,
        ];
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
