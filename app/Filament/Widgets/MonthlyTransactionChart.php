<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class MonthlyTransactionChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Monthly Transaction';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Monthly Transaction',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agus', 'Sept', 'Okt', "Nov", "Des",]
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'The number transactions created per month';
    }
}
