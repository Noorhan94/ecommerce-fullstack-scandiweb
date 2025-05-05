// components/ProductList.jsx
import React from 'react';
import ProductCard from './ProductCard.jsx';
import '../styles/ProductList.css';

const ProductList = ({ products, onQuickShop }) => {
  return (
    <div className="container">
    <div className="row">
      {products.map((product) => (
    <div className="col-sm-4" key={product.id}>
        <ProductCard key={product.id} product={product} onQuickShop={onQuickShop} />
    </div>
      ))}
    </div>
    </div>
  );
};

export default ProductList;
