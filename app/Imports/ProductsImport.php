<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule; // Import Rule class

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $category;

    public function __construct()
    {
        $this->category = Category::select('id', 'name')->get();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $category = $this->category->where('name', $row['category'])->first();

        return Product::updateOrCreate(
            ['name' => $row['name']], // Unique identifier (search criteria)
            [
                'description' => $row['description'],
                'price' => $row['price'],
                'stock' => $row['stock'],
                'category_id' => $category->id ?? null,
                'is_active' => $row['is_active'],
            ]
        );
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $category = $this->category->where('name', $value)->first();
                    if (!$category) {
                        $fail('The category "' . $value . '" does not exist.');
                    }
                },
            ],
            'is_active' => 'required|boolean',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png'
        ];
    }
}
