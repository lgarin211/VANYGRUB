import React from 'react';
import Header from '../../../components/Header';
import ProductList from '../../../components/ProductList';

const ProductPage: React.FC = () => {
  return (
    <main className="min-h-screen">
      <Header />
      <ProductList />
    </main>
  );
};

export default ProductPage;