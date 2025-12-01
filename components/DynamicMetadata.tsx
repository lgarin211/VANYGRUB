'use client';
import { useHomepageConstants } from '../hooks/useHomepageApi';
import { useEffect } from 'react';

const DynamicMetadata: React.FC = () => {
  const { constants, loading } = useHomepageConstants();

  useEffect(() => {
    if (constants && !loading) {
      // Update document title
      document.title = constants.META.TITLE;
      
      // Update meta description
      const metaDescription = document.querySelector('meta[name="description"]');
      if (metaDescription) {
        metaDescription.setAttribute('content', constants.META.DESCRIPTION);
      } else {
        const meta = document.createElement('meta');
        meta.name = 'description';
        meta.content = constants.META.DESCRIPTION;
        document.head.appendChild(meta);
      }

      // Update meta keywords
      const metaKeywords = document.querySelector('meta[name="keywords"]');
      if (metaKeywords) {
        metaKeywords.setAttribute('content', constants.META.KEYWORDS.join(', '));
      } else {
        const meta = document.createElement('meta');
        meta.name = 'keywords';
        meta.content = constants.META.KEYWORDS.join(', ');
        document.head.appendChild(meta);
      }
    }
  }, [constants, loading]);

  return null;
};

export default DynamicMetadata;