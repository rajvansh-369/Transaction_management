<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ledger;
use Carbon\Carbon;

class CronjobController extends Controller
{
    public function addTaxOnLedger()
    {
        $allLedgers = Ledger::get();
        
        foreach ($allLedgers as $ledger) {
            $totalDue = $ledger['total_due'];
            $createdAt = $ledger['created_at'];
            $currentDate = now();
            
            $differenceInDays = $createdAt->diffInDays($currentDate);
            
            if ($totalDue > 0) {
                if ($differenceInDays == 15) {
                    $interestAmount = $totalDue * 0.18;
                    $ledger->interest_amount =$interestAmount;
                    $ledger->save();
                } elseif ($differenceInDays > 15 && $differenceInDays % 3 == 0) {
                    $additionalTax = ($totalDue + $ledger->interest_amount) * 0.18;
                    
                    $interestAmount = $ledger->interest_amount + $additionalTax;
                    $ledger->interest_amount =$interestAmount;
                    $ledger->save();
                } else {
                    continue;
                }
            } else {
                continue;
            }
        }
    }
}
