<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class graficoWigets extends ChartWidget
{
    protected static ?string $heading = 'Reserva por mes';
    protected static ?int $sort = 1;
    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return [
            //
            'dataset' => [
                'label' => 'reservas',
                'data' => [2433, 3454, 4566, 3300, 5545, 5765, 6787, 8767, 7565, 8576, 9686, 8996],
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
