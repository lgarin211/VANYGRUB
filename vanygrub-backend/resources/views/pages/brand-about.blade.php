@extends('layouts.app')

@section('title', $aboutData->title ?? 'About - VANY GROUP')
@section('description', $aboutData->subtitle ?? 'Learn more about our brand and mission')

@section('content')
@php
    $colors = [
        'primary' => $aboutData->colors['primary'] ?? $aboutData->hero_data['primary_color'] ?? '#f59e0b',
        'secondary' => $aboutData->colors['secondary'] ?? $aboutData->hero_data['secondary_color'] ?? '#dc2626',
        'accent' => $aboutData->colors['accent'] ?? $aboutData->hero_data['secondary_color'] ?? '#ea580c'
    ];
    $brand = $aboutData->brand ?? 'vny';
    $heroData = $aboutData->hero_data ?? [];
    $historyData = $aboutData->history_data ?? [];
    $philosophyData = $aboutData->philosophy_data ?? [];
    $contactData = $aboutData->contact_data ?? [];
@endphp

@include('components.vny-navbar', ['currentPage' => 'about'])

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap');

    :root {
        --brand-primary: {{ $colors['primary'] }};
        --brand-secondary: {{ $colors['secondary'] }};
        --brand-accent: {{ $colors['accent'] }};
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: #fefefe;
        color: #1a1a1a;
        overflow-x: hidden;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: linear-gradient(135deg, {{ $colors['primary'] }}10, {{ $colors['secondary'] }}05);
    }

    .hero-text {
        font-size: clamp(4rem, 12vw, 12rem);
        font-weight: 100;
        letter-spacing: -0.04em;
        line-height: 0.85;
        text-align: center;
        color: #1a1a1a;
        margin-bottom: 2rem;
    }

    .hero-subtitle {
        font-size: clamp(1.2rem, 3vw, 2.5rem);
        font-weight: 200;
        letter-spacing: 0.02em;
        text-align: center;
        color: #666;
        max-width: 800px;
        margin: 0 auto;
    }

    .story-section {
        padding: 8rem 0;
        position: relative;
    }

    .story-header {
        text-align: center;
        margin-bottom: 8rem;
    }

    .story-title {
        font-size: clamp(2.5rem, 6vw, 5rem);
        font-weight: 200;
        letter-spacing: -0.02em;
        margin-bottom: 2rem;
        color: #1a1a1a;
    }

    .story-subtitle {
        font-size: 1.2rem;
        font-weight: 300;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .timeline {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #e5e5e5;
        transform: translateX(-50%);
    }

    .timeline-item {
        position: relative;
        margin: 6rem 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .timeline-item:nth-child(even) {
        direction: rtl;
    }

    .timeline-item:nth-child(even) .timeline-content {
        direction: ltr;
        text-align: right;
    }

    .timeline-year {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border: 2px solid var(--brand-primary);
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--brand-primary);
        z-index: 10;
    }

    .timeline-content h3 {
        font-size: clamp(1.5rem, 4vw, 2.8rem);
        font-weight: 300;
        letter-spacing: -0.01em;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
    }

    .timeline-content p {
        font-size: 1.1rem;
        font-weight: 300;
        line-height: 1.7;
        color: #666;
        max-width: 500px;
    }

    .timeline-image {
        width: 100%;
        height: 400px;
        background: #f8f8f8;
        border-radius: 8px;
        overflow: hidden;
    }

    .timeline-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .timeline-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        color: white;
        border-radius: 12px;
    }

    .placeholder-icon {
        margin-bottom: 1rem;
        opacity: 0.8;
    }

    .timeline-placeholder span {
        font-size: 1.1rem;
        font-weight: 500;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .philosophy-section {
        padding: 8rem 0;
        background: #fafafa;
    }

    .philosophy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 3rem;
        margin-top: 4rem;
    }

    .philosophy-image {
        width: 100%;
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: white;
    }

    .philosophy-image:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 40px rgba(0,0,0,0.15);
    }

    .philosophy-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: scale 0.3s ease;
    }

    .philosophy-image:hover img {
        scale: 1.05;
    }

    .philosophy-card {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        color: white;
        padding: 2rem;
    }

    .philosophy-content {
        text-align: center;
    }

    .philosophy-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        letter-spacing: 0.05em;
    }

    .philosophy-content p {
        font-size: 1rem;
        font-weight: 300;
        line-height: 1.6;
        opacity: 0.9;
    }

    .philosophy-card:hover {
        background: linear-gradient(135deg, var(--brand-secondary), var(--brand-primary));
    }

    .final-section {
        padding: 8rem 0;
        text-align: center;
        position: relative;
    }

    .final-title {
        font-size: clamp(3rem, 8vw, 8rem);
        font-weight: 100;
        letter-spacing: -0.03em;
        line-height: 0.9;
        margin-bottom: 3rem;
        background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .contact-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 4rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .contact-item {
        text-align: center;
        padding: 2rem;
    }

    .contact-item h4 {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .contact-item p {
        font-size: 1rem;
        font-weight: 300;
        color: #666;
    }

    @media (max-width: 768px) {
        .timeline::before {
            left: 2rem;
        }

        .timeline-item {
            grid-template-columns: 1fr;
            margin-left: 4rem;
            text-align: left;
        }

        .timeline-item:nth-child(even) {
            direction: ltr;
        }

        .timeline-item:nth-child(even) .timeline-content {
            text-align: left;
        }

        .timeline-year {
            left: 2rem;
            width: 60px;
            height: 60px;
            font-size: 0.8rem;
        }

        .container {
            padding: 0 1rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="hero-text">
            {{ $heroData['title'] ?? strtoupper($brand) }}
        </h1>
        <p class="hero-subtitle">
            {{ $heroData['subtitle'] ?? 'Discover our journey of excellence and innovation' }}
        </p>
    </div>
</section>

<!-- Story Section -->
<section class="story-section">
    <div class="container">
        <div class="story-header">
            <h2 class="story-title">{{ $historyData['main_title'] ?? 'THE STORY BEGAN' }}</h2>
            <p class="story-subtitle">
                Experience our journey of excellence through the years
            </p>
        </div>

        <div class="timeline">
            @if(isset($historyData['timeline']) && !empty($historyData['timeline']))
                @foreach($historyData['timeline'] as $index => $timelineItem)
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3>{{ strtoupper($timelineItem['title'] ?? 'Timeline Item') }}</h3>
                        <p style="color: {{ $timelineItem['color'] ?? '#666' }}">{{ $timelineItem['deskripsi'] ?? $timelineItem['description'] ?? 'Timeline description' }}</p>
                    </div>
                    <div class="timeline-image" style="background-color: {{ $timelineItem['bgcolor'] ?? '#f5f5f5' }}">
                        @if(!empty($timelineItem['poster']))
                            <img src="{{ asset('storage/' . $timelineItem['poster']) }}" alt="{{ $timelineItem['title'] ?? 'Timeline Image' }}">
                        @else
                            <div class="timeline-placeholder" style="background-color: {{ $timelineItem['bgcolor'] ?? '#f5f5f5' }}">
                                <div class="placeholder-icon" style="color: {{ $timelineItem['color'] ?? '#666' }}">
                                    <svg width="60" height="60" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <span>{{ $timelineItem['title'] ?? 'Timeline' }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="timeline-year">{{ $timelineItem['tahun'] ?? $timelineItem['year'] ?? '2024' }}</div>
                </div>
                @endforeach
            @else
                <!-- Default timeline when no data -->
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3>OUR BEGINNING</h3>
                        <p>The story of {{ $brand }} begins with a vision for excellence and innovation.</p>
                    </div>
                    <div class="timeline-image">
                        <div class="timeline-placeholder">
                            <div class="placeholder-icon">
                                <svg width="60" height="60" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            <span>Established</span>
                        </div>
                    </div>
                    <div class="timeline-year">2019</div>
                </div>
                            <p>{{ $philosophy['content'] ?? $philosophy['meaning'] ?? 'Our core belief and value.' }}</p>
                        </div>
                <div class="timeline-item">
                    <div class="timeline-content">
                        <h3>GROWTH & EXPANSION</h3>
                        <p>Continuous growth and innovation in our journey of excellence.</p>
                    </div>
                    <div class="timeline-image">
                        <div class="timeline-placeholder">
                            <div class="placeholder-icon">
                                <svg width="60" height="60" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                                </svg>
                            </div>
                            <span>Growth</span>
                        </div>
                    </div>
                    <div class="timeline-year">{{ date('Y') }}</div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Philosophy Section -->
@if(!empty($philosophyData))
<section class="philosophy-section">
    <div class="container">
        <h2 class="story-title">OUR PHILOSOPHY</h2>
        <div class="philosophy-grid">
            @if(!empty($aboutData->philosophy_images) && is_array($aboutData->philosophy_images))
                @foreach($aboutData->philosophy_images as $index => $image)
                    <div class="philosophy-image">
                        <img src="{{ asset('storage/' . $image) }}" alt="Philosophy {{ $index + 1 }}">
                    </div>
                @endforeach
            @elseif(!empty($philosophyData))
                @foreach($philosophyData as $index => $philosophy)
                    <div class="philosophy-image philosophy-card" style="background: linear-gradient(135deg, {{ $philosophy['color'] ?? '#f59e0b' }}, {{ $colors['secondary'] }});">
                        <div class="philosophy-content">
                            <h3>{{ $philosophy['name'] ?? 'Philosophy ' . ($index + 1) }}</h3>
                            <p>{{ $philosophy['meaning'] ?? 'Core value and belief' }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Default philosophy display ketika tidak ada data -->
                <div class="philosophy-image philosophy-card">
                    <div class="philosophy-content">
                        <h3>Quality Excellence</h3>
                        <p>We are committed to delivering the highest quality in everything we do.</p>
                    </div>
                </div>
                <div class="philosophy-image philosophy-card">
                    <div class="philosophy-content">
                        <h3>Innovation</h3>
                        <p>Continuously pushing boundaries to create innovative solutions.</p>
                    </div>
                </div>
                <div class="philosophy-image philosophy-card">
                    <div class="philosophy-content">
                        <h3>Customer Focus</h3>
                        <p>Our customers are at the heart of everything we do.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Final Section -->
<section class="final-section">
    <div class="container">
        <h2 class="final-title">{{ $heroData['title'] ?? strtoupper($brand) }}<br>{{ $heroData['subtitle'] ?? 'NEVER ENDS' }}</h2>

        @if(!empty($contactData))
        <div class="contact-info">
            @if(isset($contactData['email']))
            <div class="contact-item">
                <h4>Contact</h4>
                <p>{{ $contactData['email'] }}</p>
            </div>
            @endif

            @if(isset($contactData['phone']))
            <div class="contact-item">
                <h4>Phone</h4>
                <p>{{ $contactData['phone'] }}</p>
            </div>
            @endif

            @if(isset($contactData['address']))
            <div class="contact-item">
                <h4>Address</h4>
                <p>{{ $contactData['address'] }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>

<script>
// Smooth scroll animation for timeline items
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Apply initial styles and observe timeline items
document.addEventListener('DOMContentLoaded', function() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    timelineItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(50px)';
        item.style.transition = `opacity 0.8s ease ${index * 0.2}s, transform 0.8s ease ${index * 0.2}s`;
        observer.observe(item);
    });
});
</script>

@include('components.vny-footer')

@endsection
