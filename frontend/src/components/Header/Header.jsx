import React, { useContext } from "react";
import { NavLink, useLocation } from "react-router-dom";
import { CartContext } from "../../context/CartContext";
import "../../styles/Header.css";

const categories = ["all", "tech", "clothes"];

const Header = () => {
  const { cartItems, isCartOpen, setCartOpen } = useContext(CartContext);
  const location = useLocation();

  const handleCartClick = () => {
    if (cartItems.length > 0) {
      setCartOpen(!isCartOpen);
    }
  };

  const getTestId = (category) => {
    return location.pathname === `/${category}`
      ? "active-category-link"
      : "category-link";
  };

  return (
    <header className="sticky-top bg-white shadow-sm">
      <nav className="container d-flex justify-content-between align-items-center py-3">
        {/* Left - Category Links */}
        <div className="d-flex gap-4">
          {categories.map((category) => (
            <NavLink
              key={category}
              to={`/${category}`}
              className={({ isActive }) =>
                isActive ? "active-category" : "category-link"
              }
              data-testid={getTestId(category)}
            >
              {category.charAt(0).toUpperCase() + category.slice(1)}
            </NavLink>
          ))}
        </div>

        {/* Center - Brand */}
        <div className="text-center">
          <NavLink to="/" className="navbar-brand fw-bold fs-4">
            E-Store
          </NavLink>
        </div>

        {/* Right - Cart Icon */}
        <div className="position-relative">
          <button
            className="btn btn-outline-dark"
            onClick={handleCartClick}
            data-testid="cart-btn"
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
