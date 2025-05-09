import React, { useContext } from "react";
import { NavLink } from "react-router-dom";
import { CartContext } from "../../context/CartContext";
import "../../styles/Header.css";

const Header = () => {
  const { cartItems, isCartOpen, setCartOpen } = useContext(CartContext);

  const handleCartClick = () => {
    if (cartItems.length > 0) {
      setCartOpen(!isCartOpen); // toggle cart overlay
    }
  };

  return (
    <header className="sticky-top bg-white shadow-sm z-3">
      <nav className="container d-flex justify-content-between align-items-center py-3">
        {/* Left - Category Links */}
        <div className="d-flex gap-4">
          <NavLink
            to="/category/all"
            className={({ isActive }) => isActive ? "active-category" : "category-link"}
            data-testid={({ isActive }) => isActive ? "active-category-link" : "category-link"}
          >
            All
          </NavLink>
          <NavLink
            to="/category/tech"
            className={({ isActive }) => isActive ? "active-category" : "category-link"}
            data-testid={({ isActive }) => isActive ? "active-category-link" : "category-link"}
          >
            Tech
          </NavLink>
          <NavLink
            to="/category/clothes"
            className={({ isActive }) => isActive ? "active-category" : "category-link"}
            data-testid={({ isActive }) => isActive ? "active-category-link" : "category-link"}
          >
            Clothes
          </NavLink>
        </div>

        {/* Center - Logo */}
        <div className="text-center">
          <NavLink to="/" className="navbar-brand fw-bold fs-4">
            🛍️
          </NavLink>
        </div>

        {/* Right - Cart Button */}
        <div className="position-relative">
          <button
            data-testid="cart-btn"
            className={`btn ${cartItems.length === 0 ? "btn-light disabled" : "btn-outline-dark"}`}
            onClick={handleCartClick}
            disabled={cartItems.length === 0}
          >
            🛒
            {cartItems.length > 0 && (
              <span className="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                {cartItems.length}
              </span>
            )}
          </button>
        </div>
      </nav>
    </header>
  );
};

export default Header;
