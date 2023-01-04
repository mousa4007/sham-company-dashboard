<?php

namespace App\Http\Livewire;

use App\Models\AdBar;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AdBars extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public
    $paginateNumber = 5,
        $adbar,
        $image_url,
        $image_url_preview,
        $ids,
        $description,
        $checked = false,
        $selectedRows = [];


    public function render()
    {
        return view(
            'livewire.AdBars.ad-bars',
            ['ad_bars' => $this->ad_bars]
        );
    }

    public function store()
    {
        $data = $this->validate([
            'adbar' => 'required'
        ]);

        AdBar::create([
            'adbar' => $this->adbar,
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
    } //end store fucntion

    public function resetData()
    {
        $this->adbar = '';
    }

    public function edit(AdBar $adBar)
    {
        $this->ids = $adBar->id;
        $this->adbar = $adBar->adbar;
    }

    public function update()
    {
        $this->validate([
            'adbar' => 'required'
        ]);

        AdBar::find($this->ids)->update([
            'adbar' => $this->adbar,
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    } //end store fucntion

    public function getAdBarsProperty()
    {
        $query = AdBar::query();

        return $query->latest()->paginate($this->paginateNumber);
    }

    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->ad_bars->pluck('id');
        } else {
            $this->reset(['selectedRows']);
        }
    }

    public function activate()
    {
        AdBar::whereIn('id',$this->selectedRows)->each(function($q){
            $q->update(['status' => 'active']);
        });
    }

    public function disable()
    {
        AdBar::whereIn('id',$this->selectedRows)->each(function($q){
            $q->update(['status' => 'disable']);
        });
    }

    public function destroy()
    {
        AdBar::whereIn('id',$this->selectedRows)->each(function($q){
            $q->delete();
        });

        $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }



}
