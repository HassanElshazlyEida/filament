<?php

namespace App\Filament\App\Widgets;

use App\Models\Department;
use App\Models\Team;
use App\Models\User;
use App\Models\Employee;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsAppOverfiew extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', Team::find(Filament::getTenant())->first()->members->count())
            ->icon('heroicon-o-users')
            ->color('purple')
            ->description('Total Users'),
            Stat::make('Departments', Department::query()->whereBelongsTo(Filament::getTenant())->count())
                ->icon('heroicon-o-users')
                ->color('blue')
                ->description('Total Teams'),
            Stat::make('Employees',  Employee::query()->whereBelongsTo(Filament::getTenant())->count())
                ->icon('heroicon-o-users')
                ->color('green')
                ->description('Total Employees'),
        ];
    }
}
