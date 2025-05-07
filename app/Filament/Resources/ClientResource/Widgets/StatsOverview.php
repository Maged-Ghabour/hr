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
        $userId = auth()->id(); // إذا كنت تريد تصفية البيانات حسب المستخدم الحالي

        // استعلام للحصول على عدد العملاء المدخلين بواسطة كل مستخدم


        return [
            Card::make('عدد العملاء', Client::count())
                ->description('عدد العملاء في النظام'),

            Card::make('تم التواصل', Client::where('status', '1')->count())
                ->description('عدد العملاء الذين تم التواصل معهم'),

            Card::make('لم يتم التواصل ', Client::where('status', '0')->count())
                ->description('   عدد العملاء الذين لم يتم التواصل معهم'),


            Card::make('عدد الموظفين', Employee::count())
                ->description('عدد الموظفين في النظام'),

            Card::make('عدد العملاء المدخلين بواسطتي', Client::where('user_id', $userId)->count())
                ->description('عدد العملاء  الذين قمت بإدخالهم'),

        ];
    }
    protected function getLayout(): array
    {
        return [
            'widgets' => [
                'cards' => [
                    'columnSpan' => 2,  // هذه الطريقة تستخدم لتخصيص العرض عبر عمودين
                ],
            ],
        ];
    }
}
