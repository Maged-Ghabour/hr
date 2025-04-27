<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientsExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Client::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'client_name',
            'client_phone',
            'storeName_ar',
            'store_category',
            // Add other columns as needed
        ];
    }

    public function map($client): array
    {
        return [
            $client->id,
            $client->client_name,
            $client->client_phone,
            $client->storeName_ar,
            $client->store_category,
            // Map other fields
        ];
    }
}
