// Site Configuration Constants
export const SITE_CONFIG = {
  NAME: "VANYGRUB",
  TITLE: "VANYGRUB - Premium Lifestyle Collection",
  DESCRIPTION: "Discover premium lifestyle products from traditional fashion to modern hospitality services",
  URL: "http://localhost:3000",
  
  CONTACT: {
    EMAIL: "info@vanygrub.com",
    PHONE: "+62-XXX-XXXX-XXXX",
    ADDRESS: "Indonesia"
  },

  SOCIAL_MEDIA: {
    FACEBOOK: "#",
    INSTAGRAM: "#",
    TWITTER: "#",
    LINKEDIN: "#"
  },

  SEO: {
    KEYWORDS: [
      "vany",
      "premium",
      "lifestyle", 
      "fashion",
      "hospitality",
      "beauty",
      "wellness",
      "culinary",
      "travel",
      "real estate"
    ],
    AUTHOR: "VANYGRUB Team",
    LANGUAGE: "id-ID"
  }
} as const;

// Navigation Constants  
export const NAVIGATION = {
  MAIN_MENU: [
    { name: "Home", href: "/" },
    { name: "VNY", href: "/vny" },
    { name: "Gallery", href: "/gallery" }, 
    { name: "About", href: "/about" },
    { name: "Transactions", href: "/transactions" }
  ],
  
  FOOTER_LINKS: {
    COMPANY: [
      { name: "About Us", href: "/about" },
      { name: "Our Story", href: "/about" },
      { name: "Careers", href: "#" }
    ],
    SERVICES: [
      { name: "Products", href: "/vny" },
      { name: "Gallery", href: "/gallery" },
      { name: "Booking", href: "#" }
    ],
    SUPPORT: [
      { name: "Help Center", href: "#" },
      { name: "Contact Us", href: "#" },
      { name: "FAQ", href: "#" }
    ]
  }
} as const;

// API Configuration
export const API_CONFIG = {
  BASE_URL: process.env.NODE_ENV === 'production' 
    ? 'https://vanyadmin.progesio.my.id/api' 
    : 'http://localhost:8000/api',
  TIMEOUT: 10000,
  RETRY_ATTEMPTS: 3
} as const;