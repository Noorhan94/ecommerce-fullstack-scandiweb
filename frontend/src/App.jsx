import React, { useContext } from 'react';
import { useQuery, gql } from '@apollo/client';
import { Routes, Route } from 'react-router-dom';
import { CartContext } from './context/CartContext';
import ProductDetailsWrapper from './pages/ProductDetailsWrapper';
import CartOverlay from './components/CartOverlay/CartOverlay';
import Category from './pages/Category';
import Header from './components/Header/Header';
import { Navigate } from "react-router-dom"; 

import 'bootstrap/dist/css/bootstrap.min.css';

const GET_PRODUCTS = gql`
  query {
    products {
      id
      name
      price
      in_stock
      gallery
      description
      category
      attributes {
        name
        type
        items
      }
    }
  }
`;

function App() {
  const { isCartOpen } = useContext(CartContext);
  const { loading, error, data } = useQuery(GET_PRODUCTS);

  if (loading) return <p>Loading products...</p>;
  if (error) return <p>Error loading products: {error.message}</p>;

  return (
    <>
      <Header />

      {/* ✅ CartOverlay OUTSIDE of Routes */}
      {isCartOpen && <CartOverlay />}

      {/* ✅ Clean Routing */}
      <Routes>
        <Route path="/" element={<Navigate to="/all" />} />
        <Route path="/:categoryName" element={<Category />} />
        <Route path="/product/:id" element={<ProductDetailsWrapper products={data.products} />} />
      </Routes>
    </>
  );
}

export default App;
