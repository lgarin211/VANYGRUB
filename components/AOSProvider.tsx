'use client';

import { useEffect } from 'react';
import AOS from 'aos';
import 'aos/dist/aos.css';

interface AOSProviderProps {
  children: React.ReactNode;
}

const AOSProvider: React.FC<AOSProviderProps> = ({ children }) => {
  useEffect(() => {
    AOS.init({
      duration: 800,
      delay: 100,
      once: true,
      easing: 'ease-out-cubic',
      mirror: false,
      anchorPlacement: 'top-bottom',
      offset: 50,
    });

    // Refresh AOS on route changes
    AOS.refresh();

    return () => {
      AOS.refresh();
    };
  }, []);

  return <>{children}</>;
};

export default AOSProvider;