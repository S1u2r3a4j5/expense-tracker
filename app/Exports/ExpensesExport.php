<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Carbon\Carbon;


class ExpensesExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Expense::all();
        return Expense::where('user_id', auth()->id())->get();
    }
    protected $casts = [
        'date' => 'datetime',  // This ensures the 'date' field is treated as a Carbon instance
    ];

    public function headings(): array
    {
        return [
            'Title',
            'Amount',
            'Category',
            'Date',
            'Description',
        ];
    }

    public function map($expense): array
    {
        $expenseDate = Carbon::parse($expense->date); // Convert string to Carbon DateTime object

        return [
            $expense->title,
            $expense->amount,
            $expense->category->name ?? 'N/A',
            $expenseDate->format('Y-m-d'),
            $expense->description,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 15,
            'C' => 50,
            'D' => 50,
            'E' => 70,
        ];
    }
}
