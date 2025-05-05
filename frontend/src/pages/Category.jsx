import React from "react";
import { useParams } from "react-router-dom";
import { gql, useQuery } from "@apollo/client";
import ProductList from "../components/ProductList";

const GET_PRODUCTS = gql`
  query {
    products {
      id
      name
      price
      in_stock
      gallery
      category
      attributes {
        name
        type
        items
      }
    }
  }
`;

const Category = () => {
  const { categoryName } = useParams();
  const { loading, error, data } = useQuery(GET_PRODUCTS);

  if (loading) return <p>Loading products...</p>;
  if (error) return <p>Error loading products: {error.message}</p>;

  let filteredProducts = [];

  if (!categoryName || categoryName.toLowerCase() === 'all') {
    // No category provided or 'all' selected ➔ show all products
    filteredProducts = data.products;
  } else {
    // Filter by specific category
    filteredProducts = data.products.filter(
      (product) => product.category?.toLowerCase() === categoryName.toLowerCase()
    );
  }

  if (filteredProducts.length === 0) {
    return (
      <div className="container my-5">
        <h2>No products found in this category.</h2>
      </div>
    );
  }

  return (
    <div className="container my-5">
      <h2 className="mb-4 text-capitalize">
        {categoryName ? categoryName : "All Products"}
      </h2>
      <ProductList products={filteredProducts} />
    </div>
  );
};

export default Category;
