<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class EmployeeAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Employee Chart';
    protected static string $color = 'warning';
    protected static ?int $sort = 3;
    protected function getData(): array
    {
        $data = Trend::model(Employee::class)
        ->between(now()->startOfMonth(),now()->endOfMonth())
        ->perDay()
        ->count();
        return [
            'labels' => $data->map(fn(TrendValue $value)=>$value->date),
            'datasets' => [
                [
                    'label' => 'Employee Chart',
                    'data' => $data->map(fn(TrendValue $value)=>$value->aggregate),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
