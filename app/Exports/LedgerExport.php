<?php

namespace App\Exports;

use App\Models\Ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LedgerExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */


    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        // dd($this->startDate, $this->endDate); // Debugging here
    }



    public function collection()
    {

        $query = Ledger::with('customer');

        // Apply date filter if provided
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('invoice_date', [$this->startDate, $this->endDate]);
        }

        $ledgers = $query->get();
        //
        // dd( $ledgers, $this->startDate, $this->endDate);
        $exportData = $ledgers->map(function ($ledger) {
            return [
                'Customer Name' => $ledger->customer->name, // Assuming 'name' is the attribute in the Customer model you want to include
                'Customer Phone' => $ledger->customer->phone, // Assuming 'name' is the attribute in the Customer model you want to include
                // Other attributes from the Ledger model
                'bill_no' => $ledger->bill_no,
                'total_amount' => $ledger->total_amount,
                'total_credit' => $ledger->total_credit,
                'interest_amount' => $ledger->interest_amount,
                'total_due' => $ledger->total_due,
                'labour' => $ledger->labour,
                'bardana' => $ledger->bardana,
                'invoice_date' => $ledger->invoice_date,
                // Include other attributes you need
            ];
        });


        return $exportData;
    }

    public function headings(): array
    {
        return [
            'Customer Name',
            'Customer Phone',
            'Bill No',
            'Total Amount',
            'Total Credit',
            'Interest Amount',
            'Total Due',
            'Labour',
            'Bardana',
            'Invoice Date',
            // Add other headings if needed
        ];
    }
}
