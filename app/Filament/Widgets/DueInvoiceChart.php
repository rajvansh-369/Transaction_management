<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class DueInvoiceChart extends ChartWidget
{
    protected static ?string $heading = 'Un-Paid Invoice';
    protected static string $color = 'danger';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Un-Paid Invoice',
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
