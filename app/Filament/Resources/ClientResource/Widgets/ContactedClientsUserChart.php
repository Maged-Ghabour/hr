<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;

class ContactedClientsUserChart extends BarChartWidget
{
    protected static ?string $heading = 'مخطط التواصل مع العملاء حسب المستخدمين والأيام';

    protected function getFilters(): ?array
    {
        return [
            'week' => 'آخر 7 أيام',
            'month' => 'آخر 30 يوم',
            'year' => 'آخر 12 شهر',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? 'week';

        $startDate = match ($filter) {
            'month' => now()->subDays(30),
            'year' => now()->subYear(),
            default => now()->subDays(7),
        };

        // تجهيز الأيام ضمن الفترة
        $period = match ($filter) {
            'year' => 365,
            'month' => 30,
            default => 7,
        };

        $dates = collect();
        for ($i = 0; $i <= $period; $i++) {
            $date = Carbon::now()->subDays($period - $i)->format('Y-m-d');
            $dates->push($date);
        }

        // جلب البيانات
        $clients = Client::query()
            ->where('status', 1)
            ->whereDate('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, user_id, COUNT(*) as count')
            ->groupBy('date', 'user_id')
            ->orderBy('date')
            ->get();

        $users = User::whereIn('id', $clients->pluck('user_id')->unique())->get();

        $colors = [
            '#60A5FA', // blue
            '#34D399', // green
            '#FBBF24', // yellow
            '#F87171', // red
            '#A78BFA', // purple
            '#F472B6', // pink
            '#38BDF8', // sky
            '#FDBA74', // orange
        ];

        $datasets = [];
        foreach ($users as $index => $user) {
            $userData = [];

            foreach ($dates as $date) {
                $record = $clients->firstWhere(fn($c) => $c->user_id == $user->id && $c->date == $date);
                $userData[] = $record ? $record->count : 0;
            }

            $color = $colors[$index % count($colors)];

            $datasets[] = [
                'label' => 'بواسطة ' . $user->name,
                'data' => $userData,
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        }

        return [
            'labels' => $dates->map(fn($d) => Carbon::parse($d)->translatedFormat('d M'))->toArray(),
            'datasets' => $datasets,
        ];
    }
}
