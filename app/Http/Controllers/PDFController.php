<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

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

        return $pdf->download('invoice.pdf');
    }
}
