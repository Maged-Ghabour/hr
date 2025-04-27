<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use App\Models\Client;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('عدد العملاء', Client::count())
                ->description('عدد العملاء في النظام'),
            Card::make('  عدد العملاء المهتمين', Client::where('status', 'approved')->count())
                ->description('عدد العملاء في النظام'),
            Card::make('عدد الموظفين', Employee::count())
                ->description('عدد الموظفين في النظام'),
        ];
    }
}
