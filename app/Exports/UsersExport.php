<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
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
            '수정일'
        ];
    }
}
