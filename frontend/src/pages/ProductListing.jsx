import { gql, useQuery } from '@apollo/client';

const GET_PRODUCTS = gql`
  query {
    products {
      id
      name
      price
      in_stock
    }
  }
`;

export default function ProductListing() {
  const { loading, error, data } = useQuery(GET_PRODUCTS);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error: {error.message}</p>;

  return (
    <div>
      <h1>Product Listing</h1>
      <ul>
        {data.products.map(product => (
          <li key={product.id}>
            {product.name} - ${product.price.toFixed(2)} {product.in_stock ? '' : '(Out of Stock)'}
          </li>
        ))}
      </ul>
    </div>
  );
}
