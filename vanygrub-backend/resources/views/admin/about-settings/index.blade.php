@extends('layouts.admin')

@section('title', 'About Settings')
@section('page-title', 'About Settings')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Brand About Pages</h3>
        <a href="{{ route('admin.about-settings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Add New Brand
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($aboutSettings as $setting)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold" style="background: {{ $setting->colors['primary'] ?? '#f59e0b' }}">
                                {{ strtoupper(substr($setting->brand, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ strtoupper($setting->brand) }}</div>
                                <div class="text-sm text-gray-500">{{ $setting->brand }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $setting->title }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($setting->subtitle, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $setting->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $setting->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $setting->updated_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route($setting->brand . '.about') }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="{{ route('admin.about-settings.show', $setting) }}" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.about-settings.edit', $setting) }}" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.about-settings.destroy', $setting) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No about settings found. <a href="{{ route('admin.about-settings.create') }}" class="text-blue-600 hover:text-blue-800">Create the first one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($aboutSettings->hasPages())
    <div class="px-6 py-3 bg-gray-50">
        {{ $aboutSettings->links() }}
    </div>
    @endif
</div>

<!-- Brand Preview Cards -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Preview</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach(['vny', 'vanysongket', 'vanyvilla'] as $brand)
            @php
                $brandSetting = $aboutSettings->where('brand', $brand)->first();
            @endphp
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-32 bg-gradient-to-br from-gray-400 to-gray-600" style="background: linear-gradient(135deg, {{ $brandSetting ? $brandSetting->hero_data['primary_color'] ?? '#f59e0b' : '#f59e0b' }}, {{ $brandSetting ? $brandSetting->hero_data['secondary_color'] ?? '#f97316' : '#f97316' }})">
                    <div class="h-full flex items-center justify-center">
                        <h4 class="text-white text-2xl font-bold">{{ strtoupper($brand) }}</h4>
                    </div>
                </div>
                <div class="p-4">
                    @if($brandSetting)
                        <h5 class="font-semibold text-gray-800">{{ $brandSetting->title }}</h5>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($brandSetting->subtitle, 60) }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">Configured</span>
                            <div class="flex space-x-1">
                                <a href="{{ route($brand . '.about') }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="{{ route('admin.about-settings.edit', $brandSetting) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <h5 class="font-semibold text-gray-800">{{ ucfirst($brand) }}</h5>
                        <p class="text-sm text-gray-600 mt-1">Not configured yet</p>
                        <div class="mt-4">
                            <a href="{{ route('admin.about-settings.create') }}?brand={{ $brand }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-plus mr-1"></i>Configure Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
