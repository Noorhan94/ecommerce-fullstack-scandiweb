// src/components/Cart/Cart.jsx
import React, { useContext } from 'react';
import { CartContext } from '../../context/CartContext';
import kebabCase from 'lodash/kebabCase';
import './Cart.css';

const Cart = () => {
  const { cartItems } = useContext(CartContext);

  if (cartItems.length === 0) return <p>Your cart is empty 🛒</p>;

  return (
    <div className="cart-container">
      <h2 className="mb-4">🧾 Your Cart</h2>
      {cartItems.map((item, index) => (
        <div className="cart-item d-flex mb-4 gap-4 border-bottom pb-3" key={index}>
          <div className="flex-grow-1">
            <h5>{item.name}</h5>
            <p>${item.price.toFixed(2)}</p>

            {item.attributes.map((attr) => (
              <div
                key={attr.name} //capacity 
                data-testid={`cart-item-attribute-${kebabCase(attr.name)}`}
              >
                <p className="mb-1 fw-bold">{attr.name}:</p>
                <div className="d-flex gap-2 mb-2">
                  <div
                    data-testid={`cart-item-attribute-${kebabCase(attr.name)}-${kebabCase(item.selectedAttributes[attr.name])}-selected`}
                    className={`px-2 py-1 border rounded ${
                      attr.type === 'swatch'
                        ? ''
                        : 'bg-dark text-white'
                    }`}
                    style={
                      attr.type === 'swatch'
                        ? {
                            width: '25px',
                            height: '25px',
                            backgroundColor: item.selectedAttributes[attr.name],
                            border: '2px solid black',
                          }
                        : {}
                    }
                  >
                    {attr.type !== 'swatch' && item.selectedAttributes[attr.name]}
                  </div>
                </div>
              </div>
            ))}

            <p data-testid="cart-item-amount" className="mt-2">
              Quantity: {item.quantity}
            </p>
          </div>

          <div className="cart-image">
            {item.gallery?.[0] && (
              <img
                src={item.gallery[0]}
                alt={item.name}
                style={{ width: '80px', height: '80px', objectFit: 'cover' }}
              />
            )}
          </div>
        </div>
      ))}

      <div className="mt-4 text-end fw-bold" data-testid="cart-total">
        Total: $
        {cartItems
          .reduce((total, item) => total + item.price * item.quantity, 0)
          .toFixed(2)}
      </div>
    </div>
  );
};

export default Cart;
