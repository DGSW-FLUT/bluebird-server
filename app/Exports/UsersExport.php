<?php

namespace App\Exports;

use App\User;
use App\Snapshot;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings 
{
    private $idx;

    public function __construct($idx)
    {
        $this->idx = $idx;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $dump_data = Snapshot::findOrFail($this->idx);

        $dump_array = (array)json_decode($dump_data->dump_data);

        $collection = User::hydrate($dump_array);
        $collection = $collection->flatten();
        
        return $collection;
    }

    public function headings(): array
    {
        return [
            '#',
            '이름',
            '생년월일',
            '우편번호',
            '주소',
            '직업',
            '등급',
            '전화번호',
            '생성일',
            '수정일',
            '삭제일'
        ];
    }
}
