<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Pages\Actions\Action;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function getRedirectUrl(): string
    {
        // ✅ بعد الحفظ، ارجع تاني لنموذج الإضافة
        return static::getResource()::getUrl('create');
    }
    public static function canCreateAnother(): bool
    {
        return false;
    }

    public static function getValidationMessages(): array
    {
        return [
            'client_phone.unique' => 'رقم الجوال مستخدم بالفعل.',
        ];
    }
}
