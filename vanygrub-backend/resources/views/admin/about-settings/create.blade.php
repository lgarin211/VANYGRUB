@extends('layouts.admin')

@section('title', 'Create About Setting')
@section('page-title', 'Create New Brand About Page')

@section('content')
<form action="{{ route('admin.about-settings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Basic Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                <select name="brand" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Brand</option>
                    @foreach($availableBrands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                            {{ strtoupper($brand) }}
                        </option>
                    @endforeach
                </select>
                @error('brand')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-600">Active</span>
                </label>
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., VNY Toba Shoes" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
            <textarea name="subtitle" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Brand tagline or subtitle" required>{{ old('subtitle') }}</textarea>
            @error('subtitle')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Brand description and story" required>{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Design Settings -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Design & Colors</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Background</label>
                <select name="hero_background" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="amber-900">Amber (Default)</option>
                    <option value="red-900">Red</option>
                    <option value="green-900">Green</option>
                    <option value="blue-900">Blue</option>
                    <option value="purple-900">Purple</option>
                    <option value="gray-900">Gray</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                <input type="color" name="primary_color" value="{{ old('primary_color', '#f59e0b') }}" class="w-full h-10 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                <input type="color" name="secondary_color" value="{{ old('secondary_color', '#dc2626') }}" class="w-full h-10 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                <input type="color" name="accent_color" value="{{ old('accent_color', '#ea580c') }}" class="w-full h-10 border border-gray-300 rounded-lg">
            </div>
        </div>
    </div>

    <!-- History Data -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Company History</h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Established</label>
                <input type="text" name="established" value="{{ old('established', date('Y')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="2019">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Years</label>
                <input type="text" name="years" value="{{ old('years', '5+') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="5+">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customers</label>
                <input type="text" name="customers" value="{{ old('customers', '500+') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="500+">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Products</label>
                <input type="text" name="products" value="{{ old('products', '20+') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="20+">
            </div>
        </div>
    </div>

    <!-- Image Uploads -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Image Uploads</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Image</label>
                <input type="file" name="hero_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 1920x1080px</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">History Image</label>
                <input type="file" name="history_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Recommended size: 800x600px</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Philosophy Images</label>
                <input type="file" name="philosophy_images[]" accept="image/*" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Upload multiple images for philosophy section</p>
            </div>
        </div>
    </div>

    <!-- Philosophy Data -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Philosophy & Values</h3>

        <div id="philosophy-container">
            <div class="philosophy-item border border-gray-200 rounded-lg p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="philosophy_names[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Gorga">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <select name="philosophy_colors[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="amber">Amber</option>
                            <option value="red">Red</option>
                            <option value="orange">Orange</option>
                            <option value="green">Green</option>
                            <option value="blue">Blue</option>
                            <option value="purple">Purple</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meaning</label>
                        <input type="text" name="philosophy_meanings[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Meaning or description">
                    </div>
                </div>
            </div>
        </div>

        <button type="button" onclick="addPhilosophy()" class="text-blue-600 hover:text-blue-800 text-sm">
            <i class="fas fa-plus mr-1"></i>Add Another Philosophy
        </button>
    </div>

    <!-- Contact Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Contact Information</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', 'info@vanygroup.com') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', '+62 813-1587-1101') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input type="text" name="contact_location" value="{{ old('contact_location', 'Indonesia') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.about-settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Create About Setting
        </button>
    </div>
</form>

<script>
function addPhilosophy() {
    const container = document.getElementById('philosophy-container');
    const newItem = document.createElement('div');
    newItem.className = 'philosophy-item border border-gray-200 rounded-lg p-4 mb-4';
    newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="philosophy_names[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Ulos">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <select name="philosophy_colors[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="amber">Amber</option>
                    <option value="red">Red</option>
                    <option value="orange">Orange</option>
                    <option value="green">Green</option>
                    <option value="blue">Blue</option>
                    <option value="purple">Purple</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meaning</label>
                <input type="text" name="philosophy_meanings[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Meaning or description">
            </div>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 text-sm mt-2">
            <i class="fas fa-trash mr-1"></i>Remove
        </button>
    `;
    container.appendChild(newItem);
}
</script>
@endsection
