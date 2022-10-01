<?php

namespace App\Http\Livewire;

use App\Models\Ad;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Ads extends Component
{

    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public
        $image_url,
        $image_url_preview,
        $ids,
        $description,
        $checked = false,
        $selectedRows = [],
        $from,
        $to,
        $paginateNumber;

    protected $rules = [
        'description' => 'required',
        'image_url' => 'required',
    ];

    public function render()
    {
        return view('livewire.ads.ads',['ads'=>$this->ads]);
    }

    public function mount()
    {
        $this->paginateNumber = 5;
    }

    public function resetData()
    {
        $this->description = '';
        $this->image_url = '';
    }

    public function store()
    {
        $data = $this->validate();

           if($data['image_url']){
            $image = $data['image_url']->store('/','ads');
        }

        Ad::create([
            'description' => $data['description'],
            'image_url' => asset('storage/ads/'.$image),
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
    } //end store fucntion

    public function edit(Ad $ad)
    {
        $this->ids = $ad->id;
        $this->description = $ad->description;
        $this->image_url_preview = $ad->image_url;
        $this->image_url = '';
    } //end edit fucntion

    public function update()
    {
        $data = $this->validate([
            'description' => 'required',
            'image_url' => 'nullable',
        ]);

        $ad = Ad::find($this->ids);

        if ($data['image_url'] == null) {
            $ad->update([
                'description' => $data['description'],
            ]);
        } else {

            if (File::exists(public_path('storage/ads/'.explode('/' ,$this->image_url_preview)[5]))) {
                File::delete(public_path('storage/ads/'.explode('/' ,$this->image_url_preview)[5]));
            }

            if($data['image_url']){
                $image = $data['image_url']->store('/','ads');
            }
            $ad->update([
                'description' => $data['description'],
                'image_url' => asset('storage/ads/'.$image),
            ]);
        }

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    } //end update fucntion



    public function destroy()
    {
        Ad::whereIn('id', $this->selectedRows)->each(function($q){
            if (File::exists(public_path('storage/ads/'.$q->image_id))) {
                File::delete(public_path('storage/ads/'.$q->image_id));
            }

            $q->delete();
        });
        $this->image_url_preview = '';

        $this->reset(['checked']);

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }

    public function getAdsProperty()
    {
        return Ad::latest()->paginate($this->paginateNumber);
    }

    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->ads->pluck('id');
        } else {
            $this->reset(['selectedRows', 'checked']);
        }
    }


    public function disable()
    {
        Ad::whereIn('id', $this->selectedRows)->update([
            'status' => 'disabled'
        ]);
    }

    public function activate()
    {
        Ad::whereIn('id', $this->selectedRows)->update([
            'status' => 'active'
        ]);
    }


}
