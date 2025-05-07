<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ClientTable extends Component
{
    public function setSelectedRow($rowId)
    {
        session()->put('selected_row_id', $rowId); // حفظ الـ ID في الـ Session
    }
    public function render()
    {
        return view('livewire.client-table');
    }
}
