<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class TotalInvoiceChart extends ChartWidget
{
    protected static ?string $heading = 'Invoice';
    protected static ?int $sort = 2;
    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Total Invoice',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
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
