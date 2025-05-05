import React, { useState, useContext } from "react";
import { useNavigate } from "react-router-dom";
import { CartContext } from "../context/CartContext";
import kebabCase from "lodash/kebabCase";
import "../styles/ProductCard.css";

const ProductCard = ({ product }) => {
  const navigate = useNavigate();
  const { addToCart, setCartOpen } = useContext(CartContext);
  const [isHovered, setHovered] = useState(false);

  const handleQuickShop = (event) => {
    event.stopPropagation();
    event.preventDefault();

    const defaultAttributes = {};
    product.attributes.forEach(attr => {
      defaultAttributes[attr.name] = attr.items[0];
    });

    addToCart({
      product_id: product.id,
      name: product.name,
      price: product.price,
      gallery: product.gallery,
      attributes: product.attributes,
      selectedAttributes: defaultAttributes,
      quantity: 1,
    });

    setCartOpen(true);
  };

  const handleCardClick = () => {
    navigate(`/product/${product.id}`);
  };

  return (
    <div
      data-testid={`product-${kebabCase(product.name)}`}
      className="product-card p-2 position-relative"
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      onClick={handleCardClick}
      style={{ cursor: 'pointer' }}
    >  
    
    
      <div>
    {!product.in_stock && (
      <span className="outOfStockText text-capitalize fs-1 fw-bold">
        Out of Stock
      </span>
    )}
    <img
      src={product.gallery?.[0]}
      alt={product.name}
      className={`w-100 ${!product.in_stock ? "opacity-50" : ""}`}
      style={{ objectFit: "cover", height: "200px" }}
    />
    </div>

      <h5 className="mt-2">{product.name}</h5>
      <p>${product.price.toFixed(2)}</p>


      {product.in_stock && isHovered && (
        <button
          data-testid="quick-shop-button"
          type="button"
          onClick={handleQuickShop}
          className="quick-shop-btn position-absolute bottom-2 end-2 btn btn-success"
        >
          🛒
        </button>
      )}
    </div>
  );
};

export default ProductCard;
