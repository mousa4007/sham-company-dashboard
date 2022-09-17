<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


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
            // 'arrangement' => 'nullable'
        ]);

        $result = $data['image_url']->storeOnCloudinary();

        Category::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'image_url' => $result->getSecurePath(),
            'image_id' => $result->getPublicId(),
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
            $result = $data['image_url']->storeOnCloudinary();

            $category->update([
                'name' => $data['name'],
                'description' => $data['description'],
                'image_url' => $result->getSecurePath(),
                'image_id' => $result->getPublicId(),
                'arrangement' => $this->arrangement != null ? $this->arrangement : 1

            ]);
        }



        $this->dispatchBrowserEvent('hide-update-modal', ['message' => 'تم التعديل بنجاح']);
    } //end update fucntion

    public function setDeleteId($id)
    {
        $this->ids = $id;
    } //end set fucntion

    public function destroy()
    {
        Category::whereIn('id', $this->selectedRows)->delete();

        return $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
    }

    public function delete()
    {

        $category = Category::find($this->ids);


        Cloudinary::destroy($category->image_id);

        $category->delete();

        $this->dispatchBrowserEvent('hide-delete-modal', ['message' => 'تم الحذف بنجاح']);
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
