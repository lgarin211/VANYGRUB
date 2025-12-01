<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $maxSize = config('gallery.max_file_size');
        $allowedMimes = collect(config('gallery.allowed_types'))
            ->pluck('mimes')
            ->flatten()
            ->implode(',');

        return [
            'file' => [
                'required',
                'file',
                "max:{$maxSize}",
                "mimes:{$allowedMimes}"
            ],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|in:' . implode(',', config('gallery.categories')),
            'alt_text' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'sort_order' => 'integer|min:0'
        ];
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to upload.',
            'file.max' => 'The file size cannot exceed :max KB.',
            'file.mimes' => 'The file must be a valid image, video, audio, or document file.',
            'category.in' => 'The selected category is invalid.',
        ];
    }
}