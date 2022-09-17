<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class Categories extends Component
{
    use WithPagination;
    use WithFileUploads;
    protected $paginationTheme = 'bootstrap';


    public $name,
        $image_url_preview,
        $image_url,
        $ids,
        $searchTerm,
        $description,
        $arrangement,
        $category_count,
        $char_count,
        $checked = false,
        $selectedRows = [],
        $from,
        $to, $paginateNumber = 5;

    public function render()
    {
        return view('livewire.categories.categories', [
            'categories' => $this->categories
        ]);
    } //end render function

    public function mount()
    {
        $this->category_count = count(Category::all());
        $this->char_count = strlen($this->description);
        $this->paginateNumber = 5;
    }

    public function resetData()
    {
        $this->name = '';
        $this->image_url = '';
        $this->description = '';
    } //end resetData function

    public function store()
    {
        $data = $this->validate([
            'name' => 'required|string',
            'image_url' => 'required',
            'description' => 'required',
        ]);

        // $result = $data['image_url']->storeOnCloudinary();

        if($data['image_url']){
            $image = $data['image_url']->store('/','categories');
        }

        Category::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'image_url' => asset('storage/categories/' . $image),
            'image_id' => $image,
            'arrangement' => $this->arrangement != null ? $this->arrangement : 1
        ]);

        $this->dispatchBrowserEvent('hide-create-modal', ['message' => 'تم إضافة العميل بنجاح']);
    } //end store fucntion

    public function edit(Category $category)
    {
        $this->ids = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->image_url_preview = $category->image_url;
        $this->arrangement = $category->arrangement;
        $this->image_url = '';
    } //end edit fucntion

    public function update()
    {

        // dd(explode('/' ,$this->image_url_preview)[5]);

        $category = Category::find($this->ids);

        $data = $this->validate([
            'name' => 'required|string',
            'image_url' => 'nullable',
            'description' => 'required',
        ]);

        if ($data['image_url'] == null) {
            $category->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'arrangement' => $this->arrangement != null ? $this->arrangement : 1
            ]);
        } else {
            if (File::exists(public_path('storage/categories/'.explode('/' ,$this->image_url_preview)[5]))) {
                File::delete(public_path('storage/categories/'.explode('/' ,$this->image_url_preview)[5]));
            }

            if($data['image_url']){
                $image = $data['image_url']->store('/','categories');
            }

    



            $category->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'image_url' => asset('storage/categories/'.$image),
                'image_id' => $image,
                'arrangement' => $this->arrangement != null ? $this->arrangement : 1
            ]);
        }



        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    } //end update fucntion


    public function destroy()
    {
        Category::whereIn('id', $this->selectedRows)->each(function($q){
            if (File::exists(public_path('storage/categories/'.$q->image_id))) {
                File::delete(public_path('storage/categories/'.$q->image_id));
            }

            $q->delete();
        });
        $this->image_url_preview='';
        $this->reset(['checked']);

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }


    public function updatedChecked($value)
    {
        if ($value) {
            $this->selectedRows = $this->categories->pluck('id');
        } else {
            $this->reset(['selectedRows', 'checked']);
        }
    }

    public function getCategoriesProperty()
    {
        if ($this->from) {
            return Category::query()
                ->whereDate('created_at', '>=', $this->from)
                ->whereDate('created_at', '<=', $this->to)
                ->where('name', 'like', '%' . $this->searchTerm . '%')->latest()->paginate($this->paginateNumber);
        } else {
            return Category::query()

                ->where('name', 'like', '%' . $this->searchTerm . '%')->latest()->paginate($this->paginateNumber);
        }
    }

    public function disable()
    {
        Category::whereIn('id', $this->selectedRows)->update([
            'status' => 'disabled'
        ]);
    }

    public function activate()
    {
        Category::whereIn('id', $this->selectedRows)->update([
            'status' => 'active'
        ]);
    }
}
