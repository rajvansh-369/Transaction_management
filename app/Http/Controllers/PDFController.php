<?php

namespace App\Http\Controllers;

use App\Exports\LedgerExport;
use App\Models\Ledger;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use PDF;
use Illuminate\Support\Facades\App;

class PDFController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($id)
    {
        $ledger = Ledger::find($id);


        // dd($ledger);
        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            'customer' => $ledger->customer,
            'ledger' => $ledger,
            'products' => $ledger->products,
        ];

        $pdf = PDF::loadView('reciept', $data);


        $pdfName = 'invoice_' . $ledger->customer->name . '_' . $ledger->bill_no;
        // Replace spaces with underscores
        $text_with_underscores = str_replace(' ', '_', $pdfName);
        // dd($text_with_underscores );
        return $pdf->download($text_with_underscores . '.pdf');
    }


    public function exportData($date, Request $request)
    {
               // Get the previous URL
               $previousUrl = $request->headers->get('referer');

               $get_array = explode('/', $previousUrl);
            //
               if($get_array[4] != 'ledgers'){

                   $getDateArray = explode('?', $get_array[4])[1];

                   // Initialize an empty array to store the parsed data
                   $dataArray = [];

                   // Parse the string into an array
                   parse_str($getDateArray, $dataArray);

                   // Extract the date filter values
                   $startDate = isset($dataArray['tableFilters']['invoice_date']['due_from']) ? $dataArray['tableFilters']['invoice_date']['due_from'] : null;
                   $endDate = isset($dataArray['tableFilters']['invoice_date']['due_until']) ? $dataArray['tableFilters']['invoice_date']['due_until'] : null;
                //    dd($get_array);
                   $dateCond = false;
                }else{

                    $dateCond = true;
                }

        // dd($startDate);
        if ($dateCond) {

            $ledgers = Ledger::with('customer')->get();

            $exportData = $ledgers->map(function ($ledger) {
                return [
                    'Customer Name' => $ledger->customer->name, // Assuming 'name' is the attribute in the Customer model you want to include
                    // Other attributes from the Ledger model
                    'Date' => $ledger->date,
                    'Amount' => $ledger->amount,
                    // Include other attributes you need
                ];
            });

            $excel = App::make('excel');

            return $excel->download(new LedgerExport(), 'query-download.xlsx');
        } else {




                // dd( $startDate,$getDateArray );
            $excel = App::make('excel');
               return $excel->download(new LedgerExport($startDate,$endDate), 'query-download.xlsx');
            // return $excel->download(new LedgerExport($startDate,$endDate), 'query-download.xlsx');
        }

        // dd($date);
    }
}
