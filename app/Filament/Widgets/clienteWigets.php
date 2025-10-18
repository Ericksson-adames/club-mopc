<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class clienteWigets extends ChartWidget
{
    protected static ?string $heading = 'Clientes por mes';
    protected static ?int $sort = 2;
     protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return [
            //
            'dataset' => [
                'label' => 'solicitantes',
                'data' => [4344, 5676, 6798, 7890, 8987, 9388, 10343, 10524, 13664, 14345, 15753, 17332],
                'fill' => 'start',
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }


   /* protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 16,
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 5,
                    ],
                ],
            ],
        ];
    }*/
}
