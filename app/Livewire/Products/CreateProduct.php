<?php

namespace App\Livewire\Products;

use App\Livewire\Forms\ProductForm;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')] 
class CreateProduct extends Component
{
        use WithFileUploads;   

    public ProductForm $form;
    public Collection $categories;
    public ?int $category_searchable_id = null;

    public function mount()
    {
        $this->search();
    }

    public function save()
    {
        $this->form->store();

        return $this->redirect('/products');
    }

    public function search(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = Category::where('id', $this->category_searchable_id)->get();

        $this->categories = Category::query()
            ->where('name', 'like', "%$value%")
            ->take(3)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);     // <-- Adds selected option
    }

    public function render()
    {
        return view('livewire.products.create-product');
    }
}
