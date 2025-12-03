/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    remotePatterns: [
      {
        protocol: 'http',
        hostname: 'localhost',
        port: '8000',
        pathname: '/storage/**',
      },
      {
        protocol: 'http',
        hostname: '127.0.0.1',
        port: '8000', 
        pathname: '/storage/**',
      },
      {
        protocol: 'https',
        hostname: 'vanyadmin.progesio.my.id',
        pathname: '/storage/**',
      }
    ],
    // Fallback for broken images
    dangerouslyAllowSVG: true,
    contentDispositionType: 'attachment',
    contentSecurityPolicy: "default-src 'self'; script-src 'none'; sandbox;",
    // Allow unoptimized images for external domains
    unoptimized: false,
  },
  reactStrictMode: true,
  swcMinify: true,
}

module.exports = nextConfig