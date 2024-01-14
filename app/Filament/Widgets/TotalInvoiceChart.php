<?php

namespace App\Filament\Widgets;

use App\Models\Ledger;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TotalInvoiceChart extends ChartWidget
{
    protected static ?string $heading = 'Invoice';
    protected static ?int $sort = 2;
    public ?string $filter = 'today';


    protected function getData(): array
    {


        // Totals per month
        $data = Trend::model(Ledger::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Invoice',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
{
    return [
        'perDay' => 'Today',
        'perMonth' => 'Last month',
        'perYear' => 'This year',
    ];
}
}
