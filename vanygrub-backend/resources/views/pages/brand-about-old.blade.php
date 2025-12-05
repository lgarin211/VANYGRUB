@extends('layouts.app')

@section('title', $aboutData->title ?? 'About - VANY GROUP')
@section('description', $aboutData->subtitle ?? 'Learn more about our brand and mission')

@section('content')
@php
    $colors = [
        'primary' => $aboutData->hero_data['primary_color'] ?? '#f59e0b',
        'secondary' => $aboutData->hero_data['secondary_color'] ?? '#dc2626', 
        'accent' => $aboutData->hero_data['secondary_color'] ?? '#ea580c'
    ];
    $brand = $aboutData->brand ?? 'vny';
    $heroData = $aboutData->hero_data ?? ['background' => 'amber-900', 'pattern' => 'traditional'];
    $historyData = $aboutData->history_data ?? [];
    $philosophyData = $aboutData->philosophy_data ?? [];
    $contactData = $aboutData->contact_data ?? [];
@endphp

<style>
    :root {
        --brand-primary: {{ $colors['primary'] }};
        --brand-secondary: {{ $colors['secondary'] }};
        --brand-accent: {{ $colors['accent'] }};
    }
    .brand-bg-primary { background-color: var(--brand-primary); }
    .brand-bg-secondary { background-color: var(--brand-secondary); }
    .brand-bg-accent { background-color: var(--brand-accent); }
    .brand-text-primary { color: var(--brand-primary); }
    .brand-text-secondary { color: var(--brand-secondary); }
    .brand-text-accent { color: var(--brand-accent); }
</style>

<!-- Page Header -->
<section class="relative py-20 bg-gradient-to-br from-{{ $heroData['background'] ?? 'amber-900' }} via-{{ $heroData['background'] ?? 'amber-800' }} to-{{ $heroData['background'] ?? 'amber-900' }} text-white overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="absolute inset-0 brand-bg-primary opacity-10"></div>

    <!-- Pattern overlay -->
    <div class="absolute inset-0 opacity-10">
        <div class="w-full h-full bg-repeat" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"60\" height=\"60\" viewBox=\"0 0 60 60\"><g fill=\"%23ffffff\" opacity=\"0.1\"><polygon points=\"30,0 45,15 30,30 15,15\"/><polygon points=\"30,30 45,45 30,60 15,45\"/></g></svg>')"></div>
    </div>

    <div class="container mx-auto px-4 text-center relative z-10">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-amber-200 to-yellow-300 bg-clip-text text-transparent" data-aos="fade-up">
                {{ $aboutData->title ?? 'Brand Title' }}
            </h1>
            <p class="text-xl md:text-2xl opacity-90 mb-4 font-light" data-aos="fade-up" data-aos-delay="100">
                {{ $aboutData->subtitle ?? 'Brand subtitle' }}
            </p>
            <div class="w-24 h-1 brand-bg-accent mx-auto" data-aos="fade-up" data-aos-delay="200"></div>
        </div>
    </div>
</section>

<!-- History Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6" data-aos="fade-up">
                Sejarah Perjalanan
            </h2>
            <div class="w-24 h-1 brand-bg-accent mx-auto mb-8" data-aos="fade-up" data-aos-delay="100"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <div class="mb-12">
                    <h3 class="text-2xl md:text-3xl font-bold brand-text-primary mb-6">Awal Mula Perjalanan</h3>
                    <p class="text-gray-700 text-lg mb-6 leading-relaxed">
                        {{ $aboutData->description ?? 'Deskripsi tentang perjalanan brand ini...' }}
                    </p>
                    <p class="text-gray-700 text-lg mb-8 leading-relaxed">
                        Berawal dari kecintaan terhadap warisan budaya, kami percaya bahwa setiap karya memiliki cerita yang patut dilestarikan dan dibagikan kepada dunia.
                    </p>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl text-center border border-gray-200">
                        <div class="text-3xl font-bold brand-text-primary mb-2">{{ $historyData['years'] ?? '5+' }}</div>
                        <div class="text-gray-600 font-medium">Tahun Berkarya</div>
                    </div>
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl text-center border border-gray-200">
                        <div class="text-3xl font-bold brand-text-secondary mb-2">{{ $historyData['customers'] ?? '500+' }}</div>
                        <div class="text-gray-600 font-medium">Pelanggan Setia</div>
                    </div>
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl text-center border border-gray-200 col-span-2 md:col-span-1">
                        <div class="text-3xl font-bold brand-text-accent mb-2">{{ $historyData['products'] ?? '20+' }}</div>
                        <div class="text-gray-600 font-medium">Produk/Layanan</div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-left">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-3xl transform rotate-3"></div>
                    <div class="relative bg-white p-4 rounded-2xl shadow-2xl">
                        <img src="{{ asset('images/' . $brand . '-history.svg') }}" alt="{{ $aboutData->title ?? 'Brand' }} History" class="w-full h-80 object-cover rounded-xl">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-xl"></div>
                    </div>

                    <div class="absolute -bottom-4 -left-4 brand-bg-primary text-white px-6 py-3 rounded-full font-bold shadow-lg">
                        <span class="text-sm">Est. {{ $historyData['established'] ?? '2019' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Philosophy Section -->
@if($philosophyData && count($philosophyData) > 0)
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6" data-aos="fade-up">
                Filosofi & Nilai
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="100">
                Setiap karya yang kami ciptakan memiliki makna dan filosofi yang mendalam
            </p>
            <div class="w-24 h-1 brand-bg-accent mx-auto" data-aos="fade-up" data-aos-delay="200"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @foreach($philosophyData as $index => $philosophy)
            <div class="group" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="h-48 brand-bg-primary relative overflow-hidden" style="background: linear-gradient(135deg, {{ $colors['primary'] }}40, {{ $colors['secondary'] }}80)">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-32 h-32 border-4 border-white/30 rounded-full flex items-center justify-center">
                                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-{{ $philosophy['icon'] ?? 'star' }} text-white text-3xl"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('images/' . $brand . '-' . strtolower(str_replace(' ', '-', $philosophy['title'])) . '.svg') }}" alt="{{ $philosophy['title'] }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-white mb-4">{{ $philosophy['title'] }}</h3>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $philosophy['content'] }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call to Action Section -->
<section class="py-20 bg-gradient-to-br from-gray-800 via-gray-900 to-black text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="w-full h-full bg-repeat" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"80\" height=\"80\" viewBox=\"0 0 80 80\"><g fill=\"%23ffffff\" opacity=\"0.1\"><circle cx=\"40\" cy=\"40\" r=\"20\"/><path d=\"M20 20L60 60M60 20L20 60\"/></g></svg>')"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold mb-6" data-aos="fade-up">
                Bergabunglah Bersama Kami
            </h2>
            <p class="text-xl md:text-2xl opacity-90 mb-8 max-w-4xl mx-auto font-light" data-aos="fade-up" data-aos-delay="100">
                Mari bersama-sama melestarikan budaya dan menciptakan karya yang bermakna untuk generasi mendatang.
            </p>
            <div class="w-24 h-1 brand-bg-accent mx-auto mb-12" data-aos="fade-up" data-aos-delay="200"></div>
        </div>

        <!-- Contact Info -->
        @if($contactData)
        <div class="bg-black/20 backdrop-blur-sm rounded-3xl p-8 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="300">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold mb-4 text-amber-200">{{ strtoupper($brand) }} GROUP</h3>
                <p class="text-lg opacity-90 mb-6">
                    Membanggakan warisan budaya Indonesia melalui produk berkualitas tinggi dan inovasi yang berkelanjutan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-envelope mr-2 brand-text-accent"></i>
                        <span class="text-sm">{{ $contactData['email'] ?? 'info@vanygroup.com' }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-phone mr-2 brand-text-accent"></i>
                        <span class="text-sm">{{ $contactData['phone'] ?? '+62 813-1587-1101' }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-map-marker-alt mr-2 brand-text-accent"></i>
                        <span class="text-sm">{{ $contactData['location'] ?? 'Indonesia' }}</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-600/30 mt-8 pt-6 text-center">
                <p class="text-gray-200 text-sm">
                    © {{ date('Y') }} {{ strtoupper($brand) }} Group. All rights reserved. | Proudly preserving Indonesian culture through innovation.
                </p>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
