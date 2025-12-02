// Homepage Constants
export const HOMEPAGE_CONSTANTS = {
  META: {
    TITLE: "VANY GROUB - Premium Lifestyle Batak Collection",
    DESCRIPTION: "Discover premium lifestyle products from traditional fashion to modern hospitality services",
    KEYWORDS: ["vany", "premium", "lifestyle", "fashion", "hospitality", "beauty"] as string[]
  },

  HERO_SECTION: {
    TITLE: "VANY GROUB",
    SUBTITLE: "Premium Lifestyle Batak Collection",
    DESCRIPTION: "Explore our curated collection of premium products and services"
  },

  GALLERY_ITEMS: [
    {
      id: 1,
      title: 'Vany Songket',
      image: 'https://images.unsplash.com/photo-1546548970-71785318a17b?w=600&h=800&fit=crop',
      description: 'Koleksi songket tradisional dengan desain modern yang memadukan kearifan lokal dengan gaya kontemporer. Dibuat dengan benang emas dan perak berkualitas tinggi.',
      target: '/',
      category: 'Traditional Fashion'
    },
  ],

  CATEGORIES: [
    'Traditional Fashion',
    'Footwear', 
    'Hospitality',
    'Real Estate',
    'Beauty & Wellness',
    'Culinary',
    'Travel',
    'Health & Fitness',
    'Home & Living'
  ],

  COLORS: {
    PRIMARY: '#800000', // Maroon
    SECONDARY: '#000000', // Black
    ACCENT: '#ffffff', // White
    GRADIENT: 'linear-gradient(135deg, #800000 0%, #000000 100%)'
  },

  ANIMATION: {
    CAROUSEL_INTERVAL: 5000, // 5 seconds
    TRANSITION_DURATION: 300
  }
} as const;

// Type definitions
export type GalleryItem = {
  id: number;
  title: string;
  image: string;
  description: string;
  target: string;
  category: string;
};

export type HomepageColors = {
  PRIMARY: string;
  SECONDARY: string;
  ACCENT: string;
  GRADIENT: string;
};