<?php

namespace App\Http\Livewire;

use App\Models\Discount;
use Livewire\Component;
use Livewire\WithPagination;

class Discounts extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $target = 1, $name, $description, $percentage, $discountId;

    protected $rules = [
        'target' => 'required',
        'name' => 'required',
        'description' => 'required',
        'percentage' => 'required',
    ];

    public function render()
    {
        return view('livewire.discounts.discount', ['prices' => Discount::paginate(7)]);
    }

    public function store()
    {
        $this->validate();

        Discount::create([
            'target' => $this->target,
            'name' => $this->name,
            'description' => $this->description,
            'percentage' => $this->percentage,
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تمت الإضافة بنجاح']);
    }

    public function edit(Discount $discount)
    {
        $this->discountId = $discount->id;
        $this->name = $discount->name;
        $this->description = $discount->description;
        $this->percentage = $discount->percentage;
    }

    public function update()
    {
        $this->validate();

        $discount = Discount::find($this->discountId);

        $discount->update([
            // 'target' => $this->target,
            'name' => $this->name,
            'description' => $this->description,
            'percentage' => $this->percentage,
        ]);

        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    }


    public function delete($id)
    {
        Discount::find($id)->delete();
    }
}
