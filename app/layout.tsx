import './globals.css'
import 'sweetalert2/dist/sweetalert2.min.css'
import type { Metadata } from 'next'
import { Inter } from 'next/font/google'
import AOSProvider from '@/components/AOSProvider'
import CacheProvider from '@/components/CacheProvider'
import { RateLimitDebug } from '@/components/RateLimitDebug'

const inter = Inter({ subsets: ['latin'] })

export const metadata: Metadata = {
  title: 'VNY - Premium Sneakers Collection',
  description: 'Discover premium sneakers and footwear collection',
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en">
      <body className={inter.className}>
        <AOSProvider>
          <CacheProvider>
            {children}
            <RateLimitDebug />
          </CacheProvider>
        </AOSProvider>
      </body>
    </html>
  )
}