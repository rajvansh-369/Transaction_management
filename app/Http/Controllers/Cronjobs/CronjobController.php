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

        // return $allLedgers;

        foreach ($allLedgers as $ledger) {
            $totalDue = $ledger['total_due'];
            $createdAt = Carbon::parse($ledger['invoice_date']);
            $currentDate = now();

            $differenceInDays = $createdAt->diffInDays($currentDate);


            if ($totalDue > 0) {


                if ($differenceInDays > 12  &&  $ledger->sms_sent_type === NULL) {


                    $daysAdd = 15;
                    $date =  Carbon::parse($ledger['invoice_date']);
                    $date = $date->addDays($daysAdd)->format("d-M-Y");
                    $ledger->sms_sent_type = 0;
                    $ledger->save();
                    $msgTemp = "Dear " . $ledger->customer->name . "
This is a reminder about your upcoming payment due on " . $createdAt->format("d-M-Y") . " Amount : " . number_format($totalDue, 2, '.', ',') . " Please ensure that your payment is processed by due date to avoid any 18% late fees.
                    Text2";
                    // Print request parameters

                    // dd($ledger , $msgTemp);
                    $response =  Http::get('http://sms0005.smsspeed.net/http-tokenkeyapi.php?senderid=TEXTTO&route=1&templateid=1607100000000296746&authentic-key=383473616e646565706c64683130301705659905&number=' . $ledger->customer->phone . '&message= ' . $msgTemp . '');
                    // $response = "";
                    $responseArray = [

                        "ledger" => $ledger->bill_no,
                        "customer" => $ledger->customer->name,
                        "response" => $response,
                        "type" => 1

                    ];
                    \Illuminate\Support\Facades\Log::info($responseArray);

                }  if ($differenceInDays >= 15 &&  $ledger->sms_sent_type ===  0) {


                    $interestAmount = $totalDue * 0.18;
                    $ledger->interest_amount = $interestAmount;
                    $ledger->sms_sent_type = 1;
                    $msgTemp = "Dear " . $ledger->customer->name . "
This is a reminder about your upcoming payment overdue from " . $createdAt->format("d-M-Y") . " %26 Amount : " . number_format($totalDue + $interestAmount, 2, '.', ',') . " to till date. Please complete your overdue payment with 18% late fees charges.
                    Text2";
                    $response = Http::get('http://sms0005.smsspeed.net/http-tokenkeyapi.php?senderid=TEXTTO&route=1&templateid=1607100000000296747&authentic-key=383473616e646565706c64683130301705659905&number=' . $ledger->customer->phone . '&message=' . $msgTemp . '');

                    // $response = "";
                    $ledger->save();

                    $responseArray = [

                        "ledger" => $ledger->bill_no,
                        "customer" => $ledger->customer->name,
                        "response" => $response,
                        "type" => 2
                    ];
                    \Illuminate\Support\Facades\Log::info($responseArray);

                }  if ($differenceInDays > 15 && $differenceInDays % 3 == 0 &&  $ledger->sms_sent_type ===  1 &&   $ledger->sms_sent_type === 2) {



                    $additionalTax = ($totalDue + $ledger->interest_amount) * 0.18;

                    $interestAmount = $ledger->interest_amount + $additionalTax;
                    $ledger->interest_amount = $interestAmount;
                    $ledger->sms_sent_type = 2;

                    $msgTemp = "Dear " . $ledger->customer->name . "
This is a reminder about your upcoming payment overdue from " . $createdAt->format("d-M-Y") . " %26 Amount : " . number_format($totalDue + $interestAmount, 2, '.', ',') . "
to till date. Please complete your overdue payment with 18% late fees charges.
Text2";
                    $response =  Http::get('http://sms0005.smsspeed.net/http-tokenkeyapi.php?senderid=TEXTTO&route=1&templateid=1607100000000296747&authentic-key=383473616e646565706c64683130301705659905&number=' . $ledger->customer->phone . '&message=' . $msgTemp . '');
                    // $response = "";
                    $ledger->save();

                    $responseArray = [

                        "ledger" => $ledger->bill_no,
                        "customer" => $ledger->customer->name,
                        "response" => $response,
                        "type" => 3

                    ];
                    \Illuminate\Support\Facades\Log::info($responseArray);
                    // return $responseArray;
                } else {
                    continue;
                }
            } else {
                continue;
            }


        }
    }
}
