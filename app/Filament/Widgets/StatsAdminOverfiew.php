<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverfiew extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // total users, total teams, total employee
            Stat::make('Users', User::query()->count())
                ->icon('heroicon-o-users')
                ->color('purple')
                ->description('Total Users'),
            Stat::make('Teams', Team::query()->count())
                ->icon('heroicon-o-users')
                ->color('blue')
                ->description('Total Teams'),
            Stat::make('Employees', Employee::query()->count())
                ->icon('heroicon-o-users')
                ->color('green')
                ->description('Total Employees'),


        ];
    }
}
