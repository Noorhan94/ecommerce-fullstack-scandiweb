// utils/cart.js
export const addToCart = (item) => {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existing = cart.find(p =>
        p.product_id === item.product_id &&
        JSON.stringify(p.attributes) === JSON.stringify(item.attributes)
    );

    if (existing) {
        existing.quantity += item.quantity;
    } else {
        cart.push(item);
    }

    localStorage.setItem('cart', JSON.stringify(cart));
};