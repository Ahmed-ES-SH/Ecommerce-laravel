<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if ($user->role == 'Admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:50|min:6',
            'description' => 'required|min:10',
            'price' => 'required|numeric',
            'category' => 'required',
            'vendor_name' => 'string',
            'vendor_id' => 'required|numeric',
            'stock' => 'numeric',
            'discount' => 'numeric',
            'sku' => 'required|unique:products',
            'images' => 'required|array',
            'images.*' => 'file|image',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Name is required',
            'title.max' => 'Name cannot be longer than 30 characters',
            'title.min' => 'Name must be at least 6 characters',
            'description.required' => 'Description is required',
            'description.min' => 'Description must be at least 10 characters',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'category.required' => 'Category is required',
            'stock.numeric' => 'Stock must be a number',
            'discount.numeric' => 'discount must be a number',
            'sku.required' => 'SKU is required',
            'sku.unique' => 'SKU must be unique',
            'images.required' => 'Images are required',
            'images.array' => 'Images must be an array',
            'images.*.file' => 'Each image must be a file',
            'images.*.image' => 'Each image must be an image',
        ];
    }
}
