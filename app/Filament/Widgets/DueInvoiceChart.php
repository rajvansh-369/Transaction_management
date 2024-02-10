<?php

namespace App\Filament\Widgets;

use App\Models\Ledger;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class DueInvoiceChart extends ChartWidget
{
    protected static ?string $heading = 'Un-Paid Invoice';
    protected static string $color = 'danger';
    protected static ?int $sort = 2;

    protected function getData(): array
    {

         // Totals per month
         $data = Trend::query(Ledger::where("is_paid" , 0))
         ->dateColumn('invoice_date')
         ->between(
             start: now()->startOfYear(),
             end: now()->endOfYear(),
         )
         ->perMonth()
         ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Un-Paid Invoice',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'red',
                    'borderColor' => 'red',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
