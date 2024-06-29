<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class UserAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => [65, 59, 80, 81, 56, 55, 40],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
