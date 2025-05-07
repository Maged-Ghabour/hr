<?php

namespace App\Filament\Resources\ClientResource\Pages;





use App\Filament\Resources\ClientResource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\Actions\DeleteAction;
use Filament\Pages\Actions;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class EditClient extends EditRecord
{


    protected static string $resource = ClientResource::class;



    protected function getActions(): array
    {
        return [



            ButtonAction::make('whatsapp')
                ->label('واتساب')
                ->url(fn() => $this->getWhatsapp())
                ->icon('heroicon-o-chat-alt')
                ->disabled(empty($this->record->client_phone))
                ->color('secondary')
                ->openUrlInNewTab(),






            ButtonAction::make('website')
                ->label('الموقع')
                ->color('secondary')
                ->disabled(empty($this->record->website))
                ->icon('heroicon-o-globe-alt')
                ->url(fn() => $this->getWebsite())
                ->openUrlInNewTab(),


            ButtonAction::make('previous')
                ->label('السابق')
                ->url(fn() => $this->getPreviousRecordUrl())
                ->icon('heroicon-o-chevron-right')
                ->color('secondary')
                ->disabled(!$this->getPreviousRecordUrl()),

            ButtonAction::make('next')
                ->label('التالي')
                ->url(fn() => $this->getNextRecordUrl())
                ->color('secondary')
                ->icon('heroicon-o-chevron-left')
                ->disabled(!$this->getNextRecordUrl()),
            // ButtonAction::make('delete')
            //     ->label('حذف')
            //     ->action(fn() => $this->delete())
            //     ->color('danger')
            //     ->icon('heroicon-o-trash')
            //     ->requiresConfirmation()
            //     ->modalHeading('حذف العميل')
            //     ->modalSubheading('هل أنت متأكد أنك تريد حذف هذا العميل؟')
            //     ->modalButton('حذف'),


            ButtonAction::make('delete')
                ->label('حذف')
                ->action(function () {

                    $nextUrl = $this->getPreviousRecordUrl(); // نحضر الرابط قبل الحذف
                    $this->record->delete(); // نحذف العميل الحالي

                    $this->notify('success', 'تم حذف العميل بنجاح');

                    // إذا فيه سجل تالي، نروح له
                    if ($nextUrl) {
                        return redirect($nextUrl);
                    }

                    // إذا ما فيه سجل تالي، نرجع للقائمة
                    return redirect(ClientResource::getUrl());
                })
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف العميل')
                ->modalSubheading('هل أنت متأكد أنك تريد حذف هذا العميل؟')
                ->modalButton('نعم، احذف'),




        ];
    }

    protected function getPreviousRecordUrl(): ?string
    {
        $previous = $this->record::where('id', '<', $this->record->id)
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->first();

        return $previous
            ? static::getResource()::getUrl('edit', ['record' => $previous->id])
            : null;
    }

    protected function getNextRecordUrl(): ?string
    {
        $next = $this->record::where('id', '>', $this->record->id)
            ->where('user_id', Auth::id())
            ->orderBy('id', 'asc')
            ->first();

        return $next
            ? static::getResource()::getUrl('edit', ['record' => $next->id])
            : null;
    }

    protected function getWhatsapp(): ?string
    {

        $url = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $this->record->client_phone);
        return $url ? $url : null;
    }
    protected function getWebsite(): ?string
    {
        return  $this->record->website;
    }


    protected function getFooter(): ?View
    {
        return view('filament.custom.keyboard-navigation', [
            'previousUrl' => $this->getPreviousRecordUrl(),
            'nextUrl' => $this->getNextRecordUrl(),
        ]);
    }
}
