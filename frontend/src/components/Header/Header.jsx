import React, { useContext } from "react";
import { NavLink } from "react-router-dom";
import { CartContext } from "../../context/CartContext";
import "../../styles/Header.css"; // We'll make very clean small tweaks here

const Header = () => {
  const { cartItems, isCartOpen, setCartOpen } = useContext(CartContext);

  const handleCartClick = () => {
    if (cartItems.length > 0) {
      setCartOpen(!isCartOpen); // Toggle CartOverlay
    }
  };

  return (
    <header className="sticky-top bg-white shadow-sm">
      <nav className="container d-flex justify-content-between align-items-center py-3">
        {/* Left - Categories */}
        <div className="d-flex gap-4">
          <NavLink
            to="/category/all"
            className={({ isActive }) => isActive ? "active-category" : "category-link"}
            data-testid={(({ isActive }) => isActive ? "active-category-link" : "category-link")}
          >
            All
          </NavLink>
          <NavLink
            to="/category/tech"
            className={({ isActive }) => isActive ? "active-category" : "category-link"}
            data-testid={(({ isActive }) => isActive ? "active-category-link" : "category-link")}
          >
            Tech
          </NavLink>
          <NavLink
            to="/category/clothes"
            className={({ isActive }) => isActive ? "active-category" : "category-link"}
            data-testid={(({ isActive }) => isActive ? "active-category-link" : "category-link")}
          >
            Clothes
          </NavLink>
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
            className={`btn ${cartItems.length === 0 ? "btn-light disabled" : "btn-outline-dark"}`}
            style={{ position: "relative" }}
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
