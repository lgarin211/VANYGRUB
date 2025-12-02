'use client';

import React, { useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import Header from '../../components/Header';
import { preloadImages } from '../../lib/cache';

const AboutPage: React.FC = () => {
  useEffect(() => {
    // Preload images used in about page
    const aboutImages = [
      '/temp/nike-just-do-it(1).jpg',
      '/temp/nike-just-do-it(2).jpg',
      '/temp/nike-just-do-it(3).jpg'
    ];

    preloadImages(aboutImages).then(() => {
      console.log('About page: Images preloaded');
    });
  }, []);

  return (
    <div className="min-h-screen bg-white">
      <Header />

      {/* Breadcrumb */}
      <div className="py-4 bg-gray-50">
        <div className="container px-4 mx-auto">
          <div className="flex items-center space-x-2 text-sm">
            <Link href="/" className="text-red-600 hover:text-red-700">
              Home
            </Link>
            <span className="text-gray-400">/</span>
            <span className="font-medium text-gray-900">About VNY</span>
          </div>
        </div>
      </div>

      {/* Hero Section */}
      <section className="py-20 text-white bg-gradient-to-r from-red-600 to-red-800">
        <div className="container px-4 mx-auto text-center">
          <h1 className="mb-6 text-5xl font-bold">
            VNY Toba Shoes
          </h1>
          <p className="max-w-3xl mx-auto mb-8 text-xl leading-relaxed">
            Memadukan Kearifan Budaya Batak dengan Inovasi Modern dalam Setiap Langkah
          </p>
        </div>
      </section>

      {/* History Section */}
      <section className="py-16">
        <div className="container px-4 mx-auto">
          <div className="max-w-4xl mx-auto">
            <div className="mb-12 text-center">
              <h2 className="mb-4 text-4xl font-bold text-gray-900">
                Sejarah VNY Toba Shoes
              </h2>
              <div className="w-24 h-1 mx-auto mb-6 bg-red-600"></div>
            </div>

            <div className="grid items-center grid-cols-1 gap-12 lg:grid-cols-2">
              <div className="space-y-6">
                <h3 className="mb-4 text-2xl font-semibold text-red-600">
                  Awal Mula Perjalanan
                </h3>
                <p className="leading-relaxed text-gray-700">
                  VNY Toba Shoes lahir dari sebuah visi untuk melestarikan kekayaan budaya Batak melalui karya seni yang dapat dikenakan sehari-hari. Didirikan dengan semangat untuk menghidupkan kembali motif-motif tradisional Batak yang sarat makna dan filosofi.
                </p>
                <p className="leading-relaxed text-gray-700">
                  Berawal dari kecintaan terhadap warisan leluhur, kami percaya bahwa setiap motif Batak memiliki cerita yang patut dilestarikan. Dari sinilah lahir ide untuk menghadirkan sepatu dengan sentuhan motif Batak yang autentik namun tetap modern.
                </p>
              </div>
              
              <div className="relative">
                <div className="p-8 bg-red-50 rounded-2xl">
                  <Image 
                    src="/temp/nike-just-do-it(6).jpg" 
                    alt="VNY Toba Shoes History" 
                    width={400}
                    height={256}
                    className="object-cover w-full h-64 shadow-lg rounded-xl"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Philosophy Section */}
      <section className="py-16 bg-gray-50">
        <div className="container px-4 mx-auto">
          <div className="max-w-6xl mx-auto">
            <div className="mb-12 text-center">
              <h2 className="mb-4 text-4xl font-bold text-gray-900">
                Filosofi Motif Batak
              </h2>
              <p className="text-xl text-gray-600">
                Setiap motif yang kami gunakan memiliki makna dan cerita yang mendalam
              </p>
            </div>

            <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
              <div className="p-8 transition-shadow bg-white shadow-lg rounded-2xl hover:shadow-xl">
                <div className="flex items-center justify-center w-16 h-16 mb-6 bg-red-100 rounded-full">
                  <svg className="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <h3 className="mb-4 text-xl font-semibold text-gray-900">Gorga</h3>
                <p className="leading-relaxed text-gray-600">
                  Motif tradisional yang melambangkan keindahan, kemakmuran, dan perlindungan. Setiap garis dan lengkungan dalam gorga memiliki makna spiritual yang mendalam.
                </p>
              </div>

              <div className="p-8 transition-shadow bg-white shadow-lg rounded-2xl hover:shadow-xl">
                <div className="flex items-center justify-center w-16 h-16 mb-6 bg-red-100 rounded-full">
                  <svg className="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </div>
                <h3 className="mb-4 text-xl font-semibold text-gray-900">Ulos</h3>
                <p className="leading-relaxed text-gray-600">
                  Simbol kehangatan, kasih sayang, dan persatuan. Motif ulos yang kami adaptasi dalam sepatu menghadirkan rasa nyaman dan kedekatan dalam setiap langkah.
                </p>
              </div>

              <div className="p-8 transition-shadow bg-white shadow-lg rounded-2xl hover:shadow-xl">
                <div className="flex items-center justify-center w-16 h-16 mb-6 bg-red-100 rounded-full">
                  <svg className="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                  </svg>
                </div>
                <h3 className="mb-4 text-xl font-semibold text-gray-900">Singa</h3>
                <p className="leading-relaxed text-gray-600">
                  Melambangkan kekuatan, keberanian, dan kepemimpinan. Motif singa dalam desain sepatu kami memberikan energi positif dan kepercayaan diri bagi yang memakainya.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Craftsmanship Section */}
      <section className="py-16">
        <div className="container px-4 mx-auto">
          <div className="max-w-6xl mx-auto">
            <div className="grid items-center grid-cols-1 gap-12 lg:grid-cols-2">
              <div className="relative">
                <div className="grid grid-cols-2 gap-4">
                  <Image 
                    src="/temp/nike-just-do-it(7).jpg" 
                    alt="Craftsmanship 1" 
                    width={300}
                    height={192}
                    className="object-cover w-full h-48 shadow-lg rounded-xl"
                  />
                  <Image 
                    src="/temp/nike-just-do-it(8).jpg" 
                    alt="Craftsmanship 2"
                    width={300}
                    height={192} 
                    className="object-cover w-full h-48 mt-8 shadow-lg rounded-xl"
                  />
                </div>
              </div>

              <div className="space-y-6">
                <h2 className="text-4xl font-bold text-gray-900">
                  Kualitas & Kerajinan Tangan
                </h2>
                <p className="leading-relaxed text-gray-700">
                  Setiap pasang sepatu VNY Toba Shoes dibuat dengan perhatian detail yang tinggi. Kami menggabungkan teknik pembuatan sepatu modern dengan sentuhan kerajinan tangan tradisional untuk menghadirkan produk yang berkualitas premium.
                </p>
                
                <div className="space-y-4">
                  <div className="flex items-start space-x-3">
                    <div className="flex items-center justify-center flex-shrink-0 w-6 h-6 mt-1 bg-red-600 rounded-full">
                      <svg className="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-semibold text-gray-900">Bahan Premium</h4>
                      <p className="text-gray-600">Kulit asli pilihan dan material berkualitas tinggi untuk kenyamanan maksimal</p>
                    </div>
                  </div>

                  <div className="flex items-start space-x-3">
                    <div className="flex items-center justify-center flex-shrink-0 w-6 h-6 mt-1 bg-red-600 rounded-full">
                      <svg className="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-semibold text-gray-900">Motif Autentik</h4>
                      <p className="text-gray-600">Setiap motif Batak diaplikasikan dengan teknik khusus untuk mempertahankan keaslian</p>
                    </div>
                  </div>

                  <div className="flex items-start space-x-3">
                    <div className="flex items-center justify-center flex-shrink-0 w-6 h-6 mt-1 bg-red-600 rounded-full">
                      <svg className="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                      </svg>
                    </div>
                    <div>
                      <h4 className="font-semibold text-gray-900">Finishing Handmade</h4>
                      <p className="text-gray-600">Setiap detail diselesaikan dengan tangan untuk hasil yang sempurna</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Vision Mission Section */}
      <section className="py-16 bg-red-50">
        <div className="container px-4 mx-auto">
          <div className="max-w-4xl mx-auto">
            <div className="grid grid-cols-1 gap-12 md:grid-cols-2">
              <div className="p-8 bg-white shadow-lg rounded-2xl">
                <div className="flex items-center justify-center w-16 h-16 mb-6 bg-red-600 rounded-full">
                  <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </div>
                <h3 className="mb-4 text-2xl font-semibold text-gray-900">Visi Kami</h3>
                <p className="leading-relaxed text-gray-700">
                  Menjadi brand sepatu terdepan yang membanggakan warisan budaya Batak melalui inovasi desain yang mendunia, sambil tetap menjaga keaslian dan makna filosofis setiap motif tradisional.
                </p>
              </div>

              <div className="p-8 bg-white shadow-lg rounded-2xl">
                <div className="flex items-center justify-center w-16 h-16 mb-6 bg-red-600 rounded-full">
                  <svg className="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                </div>
                <h3 className="mb-4 text-2xl font-semibold text-gray-900">Misi Kami</h3>
                <p className="leading-relaxed text-gray-700">
                  Menciptakan sepatu berkualitas tinggi dengan motif Batak autentik, mengedukasi masyarakat tentang kekayaan budaya Batak, dan memberikan kontribusi positif bagi pelestarian warisan budaya Indonesia.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 text-white bg-gradient-to-r from-red-600 to-red-800">
        <div className="container px-4 mx-auto text-center">
          <h2 className="mb-6 text-4xl font-bold">
            Bergabunglah dalam Melestarikan Budaya Batak
          </h2>
          <p className="max-w-3xl mx-auto mb-8 text-xl">
            Setiap pembelian sepatu VNY Toba Shoes adalah langkah nyata Anda dalam mendukung pelestarian budaya Batak yang kaya dan bermakna.
          </p>
          <div className="flex flex-col items-center justify-center gap-4 sm:flex-row">
            <Link 
              href="/vny/product" 
              className="px-8 py-4 text-lg font-semibold text-red-600 transition-colors bg-white rounded-lg shadow-lg hover:bg-gray-100"
            >
              🛍️ Lihat Koleksi
            </Link>
            <Link 
              href="/vny" 
              className="px-8 py-4 text-lg font-semibold text-white transition-colors bg-transparent border-2 border-white rounded-lg hover:bg-white hover:text-red-600"
            >
              🏠 Kembali ke VNY Store
            </Link>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="py-12 text-white bg-gray-900">
        <div className="container px-4 mx-auto">
          <div className="grid grid-cols-1 gap-8 md:grid-cols-4">
            <div>
              <h3 className="mb-4 text-xl font-bold">VNY GROUP</h3>
              <p className="leading-relaxed text-gray-300">
                Membanggakan warisan budaya Indonesia melalui produk berkualitas tinggi dan inovasi yang berkelanjutan.
              </p>
            </div>
            
            <div>
              <h4 className="mb-4 font-semibold">Quick Links</h4>
              <ul className="space-y-2">
                <li><Link href="/" className="text-gray-300 hover:text-white">Home</Link></li>
                <li><Link href="/vny" className="text-gray-300 hover:text-white">VNY Store</Link></li>
                <li><Link href="/about" className="text-gray-300 hover:text-white">About</Link></li>
                <li><Link href="/gallery" className="text-gray-300 hover:text-white">Gallery</Link></li>
              </ul>
            </div>
            
            <div>
              <h4 className="mb-4 font-semibold">Products</h4>
              <ul className="space-y-2">
                <li><Link href="/vny/product" className="text-gray-300 hover:text-white">All Products</Link></li>
                <li><Link href="/vny/product?category=sneakers" className="text-gray-300 hover:text-white">Sneakers</Link></li>
                <li><Link href="/vny/product?category=casual" className="text-gray-300 hover:text-white">Casual</Link></li>
                <li><Link href="/vny/product?category=traditional" className="text-gray-300 hover:text-white">Traditional</Link></li>
              </ul>
            </div>
            
            <div>
              <h4 className="mb-4 font-semibold">Contact</h4>
              <ul className="space-y-2 text-gray-300">
                <li>📧 info@vnygroup.com</li>
                <li>📱 +62 813-1587-1101</li>
                <li>📍 Medan, North Sumatra</li>
              </ul>
            </div>
          </div>
          
          <div className="pt-8 mt-8 text-center border-t border-gray-800">
            <p className="text-gray-300">
              © 2025 VNY Group. All rights reserved. | Proudly preserving Batak culture through innovation.
            </p>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default AboutPage;