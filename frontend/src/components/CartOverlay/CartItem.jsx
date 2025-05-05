import React from 'react';
import './CartOverlay.css';

export default function CartItem({ item }) {
    return (
        <div className="cart-item">
            <div>
                <h4>{item.name}</h4>
                <p>${item.price.toFixed(2)}</p>

                {item.attributes.map(attr => (
                    <div
                        key={attr.key}
                        data-testid={`cart-item-attribute-${attr.key.toLowerCase().replace(/\s+/g, '-')}`}
                    >
                        <strong>{attr.key}:</strong>
                        <span data-testid={`cart-item-attribute-${attr.key.toLowerCase()}-${attr.value.toLowerCase()}`}>
                            {attr.value}
                        </span>
                    </div>
                ))}
            </div>

            <div className="quantity">
                <button data-testid="cart-item-amount-increase">+</button>
                <div data-testid="cart-item-amount">{item.quantity}</div>
                <button data-testid="cart-item-amount-decrease">-</button>
            </div>
        </div>
    );
}
