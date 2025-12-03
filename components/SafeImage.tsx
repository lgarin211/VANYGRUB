'use client';

import React, { useState } from 'react';
import Image from 'next/image';

interface SafeImageProps {
  src: string;
  alt: string;
  width?: number;
  height?: number;
  fill?: boolean;
  priority?: boolean;
  className?: string;
  sizes?: string;
  placeholder?: 'blur' | 'empty';
  style?: React.CSSProperties;
  fallbackSrc?: string;
  onError?: () => void;
}

const SafeImage: React.FC<SafeImageProps> = ({
  src,
  alt,
  width,
  height,
  fill = false,
  priority = false,
  className = '',
  sizes,
  placeholder,
  style,
  fallbackSrc = '/temp/placeholder-image.jpg',
  onError,
  ...props
}) => {
  const [imgSrc, setImgSrc] = useState(src);
  const [hasError, setHasError] = useState(false);

  const handleError = () => {
    console.warn(`Image failed to load: ${imgSrc}`);
    setHasError(true);
    
    // Try fallback image
    if (imgSrc !== fallbackSrc) {
      setImgSrc(fallbackSrc);
    }
    
    if (onError) {
      onError();
    }
  };

  // Validate image URL before rendering
  const isValidImageUrl = (url: string): boolean => {
    if (!url || typeof url !== 'string') return false;
    
    // Check if it's a valid URL format
    try {
      new URL(url);
      return true;
    } catch {
      // If not a full URL, check if it's a valid relative path
      return url.startsWith('/') || url.startsWith('./') || url.startsWith('../');
    }
  };

  // If src is invalid, use fallback immediately
  if (!isValidImageUrl(src)) {
    console.warn(`Invalid image URL provided: ${src}`);
    return (
      <div 
        className={`bg-gray-200 flex items-center justify-center ${className}`}
        style={{ width, height, ...style }}
        role="img"
        aria-label={alt || 'Image not available'}
      >
        <span className="text-gray-500 text-sm">Image not available</span>
      </div>
    );
  }

  // Render with fallback handling
  const imageProps = {
    src: imgSrc,
    alt: alt || 'Image',
    onError: handleError,
    className,
    priority,
    sizes,
    placeholder,
    style,
    ...props
  };

  if (fill) {
    return <Image {...imageProps} fill />;
  }

  return (
    <Image 
      {...imageProps}
      width={width || 500}
      height={height || 300}
    />
  );
};

export default SafeImage;