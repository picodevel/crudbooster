<?php

namespace crocodicstudio\crudbooster\exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Template1Export implements FromCollection, WithHeadings, WithMapping
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function view(): View
    {
        return view('crudbooster::export', $this->response);
    }

    public function collection()
    {
        return $this->response['result'];
    }

    public function headings(): array
    {
        $cols = $this->response['columns'];
        $headings = [];
        foreach ($cols as $col) {
            if (request()->get('columns')) {
                if (! in_array($col['label'], request()->get('columns'))) {
                    continue;
                }
            }
            $colname = $col['label'];
            array_push($headings, $colname);
        }

        return $headings;
    }

    public function map($listing): array
    {
        $cols = $this->response['columns'];
        $maps = [];
        foreach ($cols as $col) {
            if (request()->get('columns')) {
                if (! in_array($col['label'], request()->get('columns'))) {
                    continue;
                }
            }
            array_push($maps, $listing->{$col['field']});
        }

        return $maps;
    }
}
