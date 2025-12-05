@extends('layouts.admin')

@section('title', 'View About Setting - ' . ucfirst($aboutSetting->brand))

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ ucfirst($aboutSetting->brand) }} About Page Details</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.about-settings.edit', $aboutSetting) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                Edit
            </a>
            <a href="{{ route('admin.about-settings.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Back to List
            </a>
        </div>
    </div>

    <!-- Preview Link -->
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-blue-800 font-medium mr-4">Live Preview:</span>
            @php
                $previewUrl = match($aboutSetting->brand) {
                    'vny' => url('/vny/about'),
                    'vanysongket' => url('/vanysongket/about'),
                    'vanyvilla' => url('/vanyvilla/about'),
                    default => '#'
                };
            @endphp
            <a href="{{ $previewUrl }}" target="_blank"
               class="text-blue-600 hover:text-blue-800 underline">
                {{ $previewUrl }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Hero Section -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Hero Section</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Brand</label>
                    <p class="text-gray-900 capitalize font-medium">{{ $aboutSetting->brand }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Title</label>
                    <p class="text-gray-900">{{ $aboutSetting->hero_data['title'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Subtitle</label>
                    <p class="text-gray-900">{{ $aboutSetting->hero_data['subtitle'] ?? 'N/A' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Primary Color</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded border border-gray-300"
                                 style="background-color: {{ $aboutSetting->hero_data['primary_color'] ?? '#1e40af' }}"></div>
                            <span class="text-sm text-gray-700">{{ $aboutSetting->hero_data['primary_color'] ?? '#1e40af' }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Secondary Color</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded border border-gray-300"
                                 style="background-color: {{ $aboutSetting->hero_data['secondary_color'] ?? '#1d4ed8' }}"></div>
                            <span class="text-sm text-gray-700">{{ $aboutSetting->hero_data['secondary_color'] ?? '#1d4ed8' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">History Section</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Title</label>
                    <p class="text-gray-900">{{ $aboutSetting->history_data['title'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Content</label>
                    <p class="text-gray-900 text-sm leading-relaxed">{{ $aboutSetting->history_data['content'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Philosophy Section -->
        <div class="lg:col-span-2 bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Philosophy Points</h2>
            @if(!empty($aboutSetting->philosophy_data))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($aboutSetting->philosophy_data as $index => $point)
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <h3 class="font-medium text-gray-800 mb-2">{{ $point['title'] ?? 'Untitled' }}</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $point['content'] ?? 'No content' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">No philosophy points defined</p>
            @endif
        </div>

        <!-- Contact Section -->
        <div class="lg:col-span-2 bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Contact Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        <span class="text-gray-900">{{ $aboutSetting->contact_data['email'] ?? 'N/A' }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                        <span class="text-gray-900">{{ $aboutSetting->contact_data['phone'] ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Address</label>
                    <div class="flex items-start space-x-2">
                        <svg class="w-4 h-4 text-gray-500 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-900 text-sm leading-relaxed">{{ $aboutSetting->contact_data['address'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metadata -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
            <div>
                <label class="font-medium">Created:</label>
                <span>{{ $aboutSetting->created_at->format('M d, Y - H:i') }}</span>
            </div>
            <div>
                <label class="font-medium">Last Updated:</label>
                <span>{{ $aboutSetting->updated_at->format('M d, Y - H:i') }}</span>
            </div>
            <div>
                <label class="font-medium">Status:</label>
                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                    Active
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
