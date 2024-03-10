<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Ledger;
use Livewire\Component;

class AdjustInvoice extends Component
{

    public $customers;
    public $custOpt ;
    public $totalSumDue ;
    public $totalSumAmount ;
    public $activeClass ;
    public $custName  = "Customer Name";
    public $totalSumIntrest ;
    public $getInvoices=[];
    public $invoice =[];

    public $firstPage = true;
    public $secondPage = false ;

    public function mount()
    {
        $this->customers = Customer::has('ledgers')->get();;
    }

    public function getInvoice(){

        $customer = Customer::find($this->custOpt) ;

        $this->getInvoices = Ledger::where("customer_id", $this->custOpt)->get();

        $this->totalSumDue = $this->getInvoices->sum('total_due');
        $this->totalSumAmount = $this->getInvoices->sum('total_amount');
        $this->totalSumIntrest = $this->getInvoices->sum('interest_amount');
        $this->custName = $customer?->name;
        if($this->getInvoices){

            $this->activeClass = "active";
        }else{

            $this->activeClass = "";

        }

        //

        // dd($this->totalSumAmount);
    }

    public function adjustAmount(){

        $getLedgers = Ledger::whereIn('id' , $this->invoice)->get();


        foreach($getLedgers as $getLedger){


            Ledger::where('id' , $getLedger->id)->update([

                'total_credit' => $getLedger->total_amount + $getLedger->interest_amount,
                'total_due' => 0,
            ]);

        }


        $this->firstPage = false;
        $this->secondPage = true;
        // dd($ledger);
    }

    public function render()
    {
        return view('livewire.adjust-invoice');
    }
}
