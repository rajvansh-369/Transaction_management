<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ledger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

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


            // dd($totalDue, $ledger);

            if ($totalDue > 0) {
                // dd($differenceInDays, $totalDue);
                if ($differenceInDays == 15) {
                    // dd($ledger->customer);
                    $date = $ledger['created_at'];
                    $daysAdd = 15;
                    $date = $date->addDays($daysAdd)->format("d M-Y");

                    $msgTemp = "Dear " . $ledger->customer->name . "
This is a reminder about your upcoming payment due on ".$date." Amount : " . number_format($totalDue , 2 , '.' , ',') . "
Please ensure that your payment is processed by due date to avoid any 18% late fees.
Text2";
                    // dd($msgTemp);
                    $interestAmount = $totalDue * 0.18;
                    $ledger->interest_amount = $interestAmount;
                   $response =  Http::get('http://sms0005.smsspeed.net/http-tokenkeyapi.php?senderid=TEXTTO&route=1&templateid=1607100000000296746&authentic-key=383473616e646565706c64683130301705659905&number='. $ledger->customer->phone.'&message= '.$msgTemp.'');


                //    withUrlParameters([
                //     'senderid' => 'TEXTTO',
                //     'route' => '1',
                //     'templateid' => '1607100000000296747',
                //     'authentic-key' => '383473616e646565706c64683130301705659905',
                //     'number' => $ledger->customer->phone,
                //     'message' => $msgTemp
                // ])->
// Print request parameters

                    $ledger->save();
                } elseif ($differenceInDays > 15 && $differenceInDays % 3 == 0) {
                    $additionalTax = ($totalDue + $ledger->interest_amount) * 0.18;

                    $interestAmount = $ledger->interest_amount + $additionalTax;
                    $ledger->interest_amount = $interestAmount;


                    $msgTemp = "Dear " . $ledger->customer->name . "
This is a reminder about your upcoming payment overdue from " . $createdAt . " %26 Amount : " . number_format($totalDue , 2 , '.' , ',') . "
to till date. Please complete your overdue payment with 18% late fees charges.
Text2";
                    Http::get('http://sms0005.smsspeed.net/http-tokenkeyapi.php?senderid=TEXTTO&route=1&templateid=1607100000000296747&authentic-key=383473616e646565706c64683130301705659905&number='. $ledger->customer->phone.'&message='.$msgTemp.'');
                    // withUrlParameters([
                    //     'senderid' => 'TEXTTO',
                    //     'route' => '1',
                    //     'templateid' => '1607100000000296747',
                    //     'authentic-key' => '383473616e646565706c64683130301705659905',
                    //     'number' => $ledger->customer->phone,
                    //     'message' => $msgTemp
                    // ])->

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