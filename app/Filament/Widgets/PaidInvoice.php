<?php

namespace App\Filament\Widgets;

use App\Models\Ledger;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaidInvoice extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {

        $paidLedger = Ledger::where("is_paid", 1)->count();
        $notPaidLedger = Ledger::where("is_paid", 0)->count();
        $totalLedger = Ledger::count();

        return [
            Stat::make('Un-Paid Invoice', $notPaidLedger)
                ->Icon('pepicon-circle-big-filled-circle')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'red_stat',
                ])
                // ->toHtml()
                ,
            Stat::make('Paid Invoice', $paidLedger) ->Icon('pepicon-circle-big-filled-circle')
            ->color('danger')
            ->extraAttributes([
                'class' => 'green_stat',
            ]),
                // ->description('7% increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-down')
                // ->color('danger'),
            Stat::make('Total Invoice', $totalLedger),
        ];
    }
}
