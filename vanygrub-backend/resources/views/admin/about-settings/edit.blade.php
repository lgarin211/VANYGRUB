@extends('layouts.admin')

@section('title', 'Edit About Setting - ' . ucfirst($aboutSetting->brand))

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit {{ ucfirst($aboutSetting->brand) }} About Page</h1>
        <a href="{{ route('admin.about-settings.index') }}"
           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
            Back to List
        </a>
    </div>

    <form action="{{ route('admin.about-settings.update', $aboutSetting) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $aboutSetting->brand) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" readonly>
                </div>

                <!-- Main Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Main Title</label>
                    <input type="text" name="title" value="{{ old('title', $aboutSetting->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Main Subtitle -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Main Subtitle</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $aboutSetting->subtitle) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                    @error('subtitle')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>{{ old('description', $aboutSetting->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Hero Section</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Hero Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $aboutSetting->hero_data['title'] ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                    @error('hero_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hero Subtitle -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                    <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $aboutSetting->hero_data['subtitle'] ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                    @error('hero_subtitle')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Primary Hero Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Hero Color</label>
                    <input type="color" name="primary_hero_color" value="{{ old('primary_hero_color', $aboutSetting->hero_data['primary_color'] ?? '#1e40af') }}"
                           class="w-full h-10 border border-gray-300 rounded-md">
                </div>

                <!-- Secondary Hero Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Hero Color</label>
                    <input type="color" name="secondary_hero_color" value="{{ old('secondary_hero_color', $aboutSetting->hero_data['secondary_color'] ?? '#1d4ed8') }}"
                           class="w-full h-10 border border-gray-300 rounded-md">
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">History Timeline</h3>

            <!-- Main History Title -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Main History Title</label>
                <input type="text" name="main_history_title" value="{{ old('main_history_title', $aboutSetting->history_data['main_title'] ?? 'Our Story') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                @error('main_history_title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- History Timeline Items -->
            <div id="history-container">
                @php
                    $historyItems = [];
                    if ($aboutSetting->history_data && isset($aboutSetting->history_data['timeline'])) {
                        $historyItems = $aboutSetting->history_data['timeline'];
                    }
                    if (empty($historyItems)) {
                        $historyItems = [[
                            'poster' => '',
                            'tahun' => date('Y'),
                            'title' => '',
                            'deskripsi' => '',
                            'color' => '#1e40af',
                            'bgcolor' => '#f8fafc'
                        ]];
                    }
                @endphp

                @foreach($historyItems as $index => $item)
                <div class="history-item border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-800">Timeline Item {{ $index + 1 }}</h4>
                        @if($index > 0)
                        <button type="button" onclick="removeHistoryItem(this)" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-trash mr-1"></i>Remove
                        </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Poster -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Poster Image</label>
                            <input type="file" name="history_posters[]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <input type="hidden" name="history_poster_existing[]" value="{{ $item['poster'] ?? '' }}">
                            @if(isset($item['poster']) && $item['poster'])
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $item['poster']) }}" alt="Current poster" class="w-16 h-16 object-cover rounded">
                                    <p class="text-xs text-gray-500">Current poster</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tahun -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <input type="text" name="history_years[]" value="{{ old('history_years.' . $index, $item['tahun'] ?? date('Y')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="2023">
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="history_titles[]" value="{{ old('history_titles.' . $index, $item['title'] ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Company Milestone">
                        </div>

                        <!-- Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                            <input type="color" name="history_colors[]" value="{{ old('history_colors.' . $index, $item['color'] ?? '#1e40af') }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <!-- Background Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                            <input type="color" name="history_bgcolors[]" value="{{ old('history_bgcolors.' . $index, $item['bgcolor'] ?? '#f8fafc') }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <!-- Deskripsi -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="history_descriptions[]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Describe this milestone...">{{ old('history_descriptions.' . $index, $item['deskripsi'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" onclick="addHistoryItem()" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-plus mr-1"></i>Add History Item
            </button>
        </div>

        <!-- Philosophy & Values -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Philosophy & Values</h3>

            <div id="philosophy-container">
                @php
                    $philosophyData = [];
                    if ($aboutSetting->philosophy_data) {
                        foreach($aboutSetting->philosophy_data as $item) {
                            $philosophyData[] = [
                                'color' => $item['color'] ?? '#1e40af',
                                'name' => $item['name'] ?? '',
                                'meaning' => $item['meaning'] ?? $item['value'] ?? ''
                            ];
                        }
                    }
                    if (empty($philosophyData)) {
                        $philosophyData = [['color' => '#1e40af', 'name' => '', 'meaning' => '']];
                    }
                @endphp

                @foreach($philosophyData as $index => $item)
                <div class="philosophy-item border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-800">Philosophy Item {{ $index + 1 }}</h4>
                        @if($index > 0)
                        <button type="button" onclick="removePhilosophyItem(this)" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="fas fa-trash mr-1"></i>Remove
                        </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                            <input type="color" name="philosophy_colors[]" value="{{ old('philosophy_colors.' . $index, $item['color']) }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="philosophy_names[]" value="{{ old('philosophy_names.' . $index, $item['name']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="e.g., Quality">
                        </div>

                        <!-- Value/Meaning -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Value / Meaning</label>
                            <input type="text" name="philosophy_meanings[]" value="{{ old('philosophy_meanings.' . $index, $item['meaning']) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Core value meaning">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" onclick="addPhilosophyItem()" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-plus mr-1"></i>Add Philosophy Item
            </button>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Contact Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $aboutSetting->contact_data['email'] ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                    @error('contact_email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $aboutSetting->contact_data['phone'] ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                    @error('contact_phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" name="contact_location" value="{{ old('contact_location', $aboutSetting->contact_data['location'] ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
                    @error('contact_location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.about-settings.index') }}"
               class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                Cancel
            </a>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                Update About Setting
            </button>
        </div>
    </form>
</div>

<script>
// History Timeline Functions
function addHistoryItem() {
    const container = document.getElementById('history-container');
    const itemCount = container.children.length + 1;
    const newItem = document.createElement('div');
    newItem.className = 'history-item border border-gray-200 rounded-lg p-4 mb-4';
    newItem.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-md font-medium text-gray-800">Timeline Item ${itemCount}</h4>
            <button type="button" onclick="removeHistoryItem(this)" class="text-red-600 hover:text-red-800 text-sm">
                <i class="fas fa-trash mr-1"></i>Remove
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Poster Image</label>
                <input type="file" name="history_posters[]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <input type="hidden" name="history_poster_existing[]" value="">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <input type="text" name="history_years[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="2023">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="history_titles[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Company Milestone">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Text Color</label>
                <input type="color" name="history_colors[]" value="#1e40af" class="w-full h-10 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Background Color</label>
                <input type="color" name="history_bgcolors[]" value="#f8fafc" class="w-full h-10 border border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="history_descriptions[]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Describe this milestone..."></textarea>
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeHistoryItem(button) {
    button.closest('.history-item').remove();
}

// Philosophy Functions
function addPhilosophyItem() {
    const container = document.getElementById('philosophy-container');
    const itemCount = container.children.length + 1;
    const newItem = document.createElement('div');
    newItem.className = 'philosophy-item border border-gray-200 rounded-lg p-4 mb-4';
    newItem.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-md font-medium text-gray-800">Philosophy Item ${itemCount}</h4>
            <button type="button" onclick="removePhilosophyItem(this)" class="text-red-600 hover:text-red-800 text-sm">
                <i class="fas fa-trash mr-1"></i>Remove
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <input type="color" name="philosophy_colors[]" value="#1e40af" class="w-full h-10 border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="philosophy_names[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="e.g., Quality">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Value / Meaning</label>
                <input type="text" name="philosophy_meanings[]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" placeholder="Core value meaning">
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removePhilosophyItem(button) {
    button.closest('.philosophy-item').remove();
}
</script>
@endsection
