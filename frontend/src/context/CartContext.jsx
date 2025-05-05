import React, { useState, useEffect ,createContext} from 'react';

export const CartContext = createContext();

export const CartProvider = ({ children }) => {
  const [isCartOpen, setCartOpen] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState(null);

  const [cartItems, setCartItems] = useState(() => {
    const saved = localStorage.getItem("cart");
    return saved ? JSON.parse(saved) : [];
  });
  
  useEffect(() => {
    localStorage.setItem("cart", JSON.stringify(cartItems));
  }, [cartItems]);
  

  const addToCart = (newItem) => {
    const itemKey = `${newItem.product_id}-${JSON.stringify(newItem.selectedAttributes)}`;

    const existingIndex = cartItems.findIndex(
      (item) =>
        item.product_id === newItem.product_id &&
        JSON.stringify(item.selectedAttributes) === JSON.stringify(newItem.selectedAttributes)
    );

    if (existingIndex !== -1) {
      const updatedCart = [...cartItems];
      updatedCart[existingIndex].quantity += newItem.quantity;
      setCartItems(updatedCart);
    } else {
      setCartItems((prev) => [...prev, { ...newItem }]);
    }
  };

  const increaseQty = (index) => {
    const updatedCart = [...cartItems];
    updatedCart[index].quantity += 1;
    setCartItems(updatedCart);
  };

  const decreaseQty = (index) => {
    const updatedCart = [...cartItems];
    if (updatedCart[index].quantity > 1) {
      updatedCart[index].quantity -= 1;
      setCartItems(updatedCart);
    } else {
      updatedCart.splice(index, 1);
      setCartItems(updatedCart);
    }
  };
  

  return (
    <CartContext.Provider value={{ 
      cartItems, 
      addToCart, 
      increaseQty, 
      decreaseQty, 
      isCartOpen, 
      setCartOpen, 
      selectedProduct, 
      setCartItems,
      setSelectedProduct
    }}>
      {children}
    </CartContext.Provider>
  );
};
