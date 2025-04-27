<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use App\Models\Client;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class ClientsChart extends BarChartWidget
{
    protected static ?string $heading = 'مخطط العملاء الجدد خلال الأسبوع الماضي';


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
        $filter = $this->filter; // القيمة المختارة من المستخدم

        $query = \App\Models\Client::query();

        // نحدد التاريخ حسب الفلتر
        if ($filter === 'month') {
            $query->whereDate('created_at', '>=', now()->subDays(30));
        } elseif ($filter === 'year') {
            $query->whereDate('created_at', '>=', now()->subYear());
        } else {
            $query->whereDate('created_at', '>=', now()->subDays(7)); // default: week
        }

        $clients = $query
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'عدد العملاء',
                    'data' => $clients->pluck('count')->toArray(),
                    'borderColor' => 'rgba(34,197,94,1)',
                    'backgroundColor' => 'rgba(34,197,94,0.2)',
                ],
            ],
            'labels' => $clients->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->translatedFormat('d M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // أو 'bar' لو تبي مخطط أعمدة
    }
    protected function getHeight(): string
    {
        return '300px'; // ارتفاع المخطط
    }
    protected function getWidth(): string
    {
        return '100%'; // عرض المخطط
    }
}
