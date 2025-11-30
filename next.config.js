/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    domains: ['localhost', '127.0.0.1'],
  },
  reactStrictMode: true,
  swcMinify: true,
}

module.exports = nextConfig