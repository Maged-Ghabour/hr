<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use Filament\Widgets\BarChartWidget;

class UserChart extends BarChartWidget
{
    protected static ?string $heading = 'مخطط العملاء الجدد حسب المستخدمين';

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
        $filter = $this->filter;

        $query = \App\Models\Client::query();

        if ($filter === 'month') {
            $query->whereDate('created_at', '>=', now()->subDays(30));
        } elseif ($filter === 'year') {
            $query->whereDate('created_at', '>=', now()->subYear());
        } else {
            $query->whereDate('created_at', '>=', now()->subDays(7));
        }

        $clients = $query
            ->selectRaw('DATE(created_at) as date, user_id, COUNT(*) as count')
            ->groupBy('date', 'user_id')
            ->orderBy('date')
            ->get();

        $groupedData = [];

        foreach ($clients as $client) {
            $date = $client->date;
            $userId = $client->user_id;

            if (!isset($groupedData[$userId])) {
                $groupedData[$userId] = [];
            }

            $groupedData[$userId][$date] = $client->count;
        }

        $allDates = $clients->pluck('date')->unique()->sort()->values();

        $datasets = [];

        foreach ($groupedData as $userId => $dataByDate) {
            $user = \App\Models\User::find($userId);

            if (!$user) {
                continue;
            }

            $data = [];

            foreach ($allDates as $date) {
                $data[] = $dataByDate[$date] ?? 0;
            }

            $color = $this->getColorFromUserId($userId);

            $datasets[] = [
                'label' => 'عدد العملاء بواسطة ' . $user->name,
                'data' => $data,
                'backgroundColor' => $color['background'],
                'borderColor' => $color['border'],
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $allDates->map(fn($date) => \Carbon\Carbon::parse($date)->translatedFormat('d M'))->toArray(),
        ];
    }

    private function getColorFromUserId($userId): array
    {
        $hash = substr(md5($userId), 0, 6);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        return [
            'border' => "rgba($r, $g, $b, 1)",
            'background' => "rgba($r, $g, $b, 0.4)",
        ];
    }
}
