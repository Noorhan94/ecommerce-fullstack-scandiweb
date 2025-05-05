// src/components/CartOverlay/CartOverlay.jsx
import React, { useContext } from "react";
import { CartContext } from "../../context/CartContext";
import kebabCase from "lodash/kebabCase";
import { gql, useMutation } from "@apollo/client";

const CartOverlay = () => {


  const { cartItems,setCartItems, increaseQty, decreaseQty, setCartOpen } = useContext(CartContext);

  const totalPrice = cartItems.reduce((acc, item) => acc + item.price * item.quantity, 0).toFixed(2);
  if (cartItems.length === 0) return null; // don't show overlay if cart empty
  const items = cartItems.map(item => ({
    product_id: item.product_id,
    quantity: item.quantity,
    price: item.price, // ✅ You MUST include this
    attributes: Object.entries(item.selectedAttributes).map(([key, value]) => ({
      key,
      value,
    })),
  }));
  const handleClose = () => {
    setCartOpen(false);
  };
  
  const CREATE_ORDER = gql`
  mutation CreateOrder($total_price: Float!, $items: [OrderItemInput!]!) {
    createOrder(total_price: $total_price, items: $items) {
      id
      total_price
      items {
        product_id
        quantity
        price
        attributes {
          key
          value
        }
      }
    }
  }
`;

const [createOrder] = useMutation(CREATE_ORDER);

const handlePlaceOrder = async () => {
  if (cartItems.length === 0) return;

  try {
    const formattedItems = cartItems.map((item) => ({
      product_id: item.product_id,
      quantity: item.quantity,
      price: item.price, // ✅ Include price
      attributes: Object.entries(item.selectedAttributes).map(([key, value]) => ({
        key,
        value
      }))
    }));

    const { data } = await createOrder({
      variables: {
        total_price: parseFloat(totalPrice),
        items: formattedItems
      }
    });

    if (data?.createOrder?.id) {
      alert("✅ Order placed successfully!");
      setCartItems([]); // clear cart
      setCartOpen(false);
    } else {
      alert("⚠️ Failed to place order.");
    }
  } catch (err) {
    console.error("❌ Order failed", err);
    alert("❌ Order error.");
  }
};

 
  

  return (
    <div className="position-fixed  start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex justify-content-end" style={{ zIndex: 1050 }}>
      <div className="bg-white itemDiv" style={{ width: "400px", borderRadius: "5px", maxHeight: "100vh", boxShadow: "0 4px 10px rgba(0,0,0,0.3)" }}>
        
        {/* Header */}
        <div className="cartHeader">
          <div className=" d-flex justify-content-between align-items-center mb-4">
          <h5 className="mb-0">
            My Bag ({cartItems.length} {cartItems.length === 1 ? 'Item' : 'Items'})
          </h5>
            <button className="btn-close" onClick={handleClose}></button>
          </div>
        </div>

        <div className="p-4" style={{ maxHeight: "70vh", overflowY: "auto" }}>
        {/* Cart Items */}
        {cartItems.map((item, index) => (
          <div key={`${item.product_id}-${index}`} className=" border-bottom mb-4 pb-3">
            <div className="row">
              {/* Left Column: Attributes */}
              <div className="col-5">
                <h6 className="fw-bold">{item.name}</h6>
                <p>${item.price.toFixed(2)}</p>
                {item.attributes.map(attr => (
                  <div key={attr.name} data-testid={`cart-item-attribute-${kebabCase(attr.name)}`} className="mb-2">
                    <p className="fw-bold mb-1">{attr.name}:</p>
                    <div className="d-flex flex-wrap gap-2">
                      {attr.items.map(opt => {
                        const isSelected = item.selectedAttributes[attr.name] === opt;
                        const baseTestId = `cart-item-attribute-${kebabCase(attr.name)}-${kebabCase(opt)}`;
                        const selectedTestId = isSelected ? `${baseTestId}-selected` : baseTestId;

                        return (
                          <div   key={`border-${kebabCase(opt)}` }           
                          style={{
                           border : attr.type === 'swatch' && isSelected ? '2px solid green' : '',
                           padding : attr.type === 'swatch' && isSelected ? '2px' : '' }}
                           
                         >
 
                            <div
                              key={opt}
                              data-testid={selectedTestId}
                              className={`border p-1 ${isSelected && attr.type !== 'swatch'  ? 'bg-dark text-white' : ''}`}
                              style={attr.type === 'swatch' ? { backgroundColor: opt, width: "24px", height: "24px", border: "2px solid black" } : {}}
                            >
                              {attr.type !== 'swatch' && opt}
                            </div>
                          </div>

                        );
                      })}
                    </div>
                  </div>
                ))}
              </div>

              {/* Middle Column: Quantity */}
              <div className="col-3 d-flex flex-column align-items-center justify-content-between">
                <button onClick={() => increaseQty(index)} data-testid="cart-item-amount-increase" className="btn btn-outline-secondary btn-sm" style={{ width: "25px", height: "25px", padding: "0" }}>+</button>
                <span data-testid="cart-item-amount" className="fw-bold">{item.quantity}</span>
                <button onClick={() => decreaseQty(index)} data-testid="cart-item-amount-decrease" className="btn btn-outline-secondary btn-sm" style={{ width: "25px", height: "25px", padding: "0" }}>-</button>
              </div>

              {/* Right Column: Image */}
              <div className="col-4 d-flex justify-content-center align-items-center">
                {item.gallery?.[0] && (
                  <img src={item.gallery[0]} alt={item.name} className="border rounded" style={{ width: "70px", height: "70px", objectFit: "cover" }} />
                )}
              </div>
            </div>
          </div>
        ))}
        {/* Total */}
        <div className="text-end fw-bold" data-testid="cart-total">
          Total: ${totalPrice}
        </div>
        <button
          onClick={handlePlaceOrder}
          disabled={cartItems.length === 0}
          className="btn btn-success mt-3 w-100"
        >
          Place Order
        </button>

        </div>

      </div>
    </div>
  );
};

export default CartOverlay;
