<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Str; 

class ProductForm extends Form
{ 
    public Product $product;

    #[Validate('required|min:3|max:255')]
    public $name;

    public $description;

    #[Validate('required|numeric|min:0')]
    public $price;

    #[Validate('required|numeric|min:0')]
    public $stock;

    #[Validate('required|min:3|max:255')]
    public $sku;

    public $is_active = true;

    #[Validate('required|exists:categories,id')]
    public $category_id;

    #[Validate('nullable|image|max:1024')] // Not required on edit
    public $image_path;

    

    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->fill($product->toArray());
    }

    public function update()
    {
        $this->validate();

        if ($this->image_path) {
            $image_name = Str::slug($this->sku) .'-'. time() . '.' . $this->image_path->extension();
            $this->image_path->storeAs('images', $image_name, 'public');
            $this->image_path = 'images/' . $image_name;
        } else {
            // Keep the current image_path in the database
            $this->image_path = $this->product->image_path;
        }
        $this->product->update($this->all());

    }

    public function store()
    {
        $this->validate();

        $image_name = Str::slug($this->sku) .'-'. time() . '.' . $this->image_path->extension();
        $this->image_path->storeAs('images', $image_name, 'public');
        $this->image_path = 'images/' . $image_name;

        Product::create($this->all());
        session()->flash('message', 'Product created successfully.');
    }
}
