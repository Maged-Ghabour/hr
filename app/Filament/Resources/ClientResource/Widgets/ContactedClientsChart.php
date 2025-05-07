<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Client;
use Carbon\Carbon;

class ContactedClientsChart extends BarChartWidget
{
    protected static ?string $heading = 'مخطط التواصل مع العملاء يومياً';

    protected function getData(): array
    {
        $clients = Client::query()
            ->where('status', 1) // فقط العملاء الذين تم التواصل معهم
            ->whereDate('created_at', '>=', now()->subDays(30)) // آخر 30 يوم
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        // تجهيز قائمة الأيام (حتى الأيام بدون بيانات)
        $dates = collect();
        for ($i = 0; $i <= 30; $i++) {
            $date = Carbon::now()->subDays(30 - $i)->format('Y-m-d');
            $dates->push($date);
        }

        foreach ($dates as $date) {
            $labels[] = Carbon::parse($date)->translatedFormat('d M');
            $record = $clients->firstWhere('date', $date);
            $data[] = $record ? $record->count : 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'عدد العملاء المتواصل معهم',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.6)', // أزرق
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                ],
            ],
        ];
    }
}
