import React, { useState } from 'react';
import { useParams } from 'react-router-dom';
import { gql, useQuery } from '@apollo/client';
import { CartContext } from '../context/CartContext';
import { useContext } from 'react';
import '../styles/ProductDetails.css';
import parse from 'html-react-parser';


const GET_PRODUCT = gql`
  query getProduct($id: String!) {
    product(id: $id) {
      id
      name
      description
      gallery
      price
      in_stock
      attributes {
        name
        type
        items
      }
    }
  }
`;

const ProductDetails = () => {
  const { id } = useParams();
  const { loading, error, data } = useQuery(GET_PRODUCT, { variables: { id } });
  const { addToCart, setCartOpen } = useContext(CartContext);

  const [selectedImageIndex, setSelectedImageIndex] = useState(0);
  const [selectedAttributes, setSelectedAttributes] = useState({});

  if (loading) return <p>Loading product...</p>;
  if (error) return <p>Error loading product: {error.message}</p>;

  const product = data.product;

  const handleAttributeChange = (name, value) => {
    setSelectedAttributes(prev => ({ ...prev, [name]: value }));
  };

  const handleAddToCart = () => {
    if (Object.keys(selectedAttributes).length !== product.attributes.length) {
      alert('Please select all attributes!');
      return;
    }

    addToCart({
      product_id: product.id,
      name: product.name,
      price: product.price,
      gallery: product.gallery,
      attributes: product.attributes,
      selectedAttributes,
      quantity: 1,
      in_stock : product.in_stock,
    });

    setCartOpen(true);
  };

  const allAttributesSelected = Object.keys(selectedAttributes).length === product.attributes.length;

  const nextImage = () => {
    setSelectedImageIndex((prev) => (prev + 1) % product.gallery.length);
  };

  const prevImage = () => {
    setSelectedImageIndex((prev) => (prev - 1 + product.gallery.length) % product.gallery.length);
  };

  return (
    <div className="container my-4">
      <div className="row">

        {/* Gallery Left Side */}
        <div className="col-md-6 d-flex">
          <div className="thumbnail-list d-flex flex-column me-3" data-testid="product-gallery">
            {product.gallery.map((img, idx) => (
              <img
                key={idx}
                src={img}
                alt={`thumbnail-${idx}`}
                className={`thumbnail ${selectedImageIndex === idx ? 'selected' : ''}`}
                onClick={() => setSelectedImageIndex(idx)}
              />
            ))}
          </div>

          {/* Main Image + Arrows */}
          <div className="position-relative w-100">
            <img
              src={product.gallery[selectedImageIndex]}
              alt="Selected"
              className="main-image w-100"
              style={{ maxHeight: '500px', objectFit: 'contain' }}
            />
            {/* Arrows */}
            <button className="carousel-arrow left" onClick={prevImage}>
              ‹
            </button>
            <button className="carousel-arrow right" onClick={nextImage}>
              ›
            </button>
          </div>
        </div>

        {/* Details Right Side */}
        <div className="col-md-6">
          <h2 className="mb-3">{product.name}</h2>
          <p className="h5 mb-4">${product.price.toFixed(2)}</p>

          {/* Attributes */}
          {product.attributes.map((attr) => (
            <div key={attr.name} className="mb-4" data-testid={`product-attribute-${attr.name.replace(/\s+/g, '-').toLowerCase()}`}>
              <p className="fw-bold">{attr.name}:</p>
              <div className="d-flex gap-2 flex-wrap">
                {attr.items.map((item) => {
                  const isSelected = selectedAttributes[attr.name] === item;
                  return (
                      <div key={`border-${(item)}` }           
                        style={{
                        border : attr.type === 'swatch' && isSelected ? '2px solid green' : '',
                        padding : attr.type === 'swatch' && isSelected ? '2px' : '' }}
                        
                        >
                            <button
                            key={item}
                            className={`attribute-btn ${isSelected ? 'selected' : ''}`}
                            style={
                                attr.type === 'swatch'
                                ? { backgroundColor: item, width: '30px', height: '30px' }
                                : {}
                            }
                            onClick={() => handleAttributeChange(attr.name, item)}
                            >
                      {attr.type !== 'swatch' && item}
                    </button>
                    </div>
                  );
                })}
              </div>
            </div>
          ))}

          {/* Add to Cart */}
          <button
            className="btn btn-dark w-100"
            data-testid="add-to-cart"
            onClick={handleAddToCart}
            disabled={!product.in_stock || Object.keys(selectedAttributes).length !== product.attributes.length}
            >
            {product.in_stock ? 'Add to Cart' : 'Out of Stock'}
            </button>

          {/* Description */}
          <div className="mt-4" data-testid="product-description">
            <p>  {parse(product.description)}   </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetails;
