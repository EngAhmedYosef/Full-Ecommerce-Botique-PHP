CREATE OR REPLACE VIEW orders_cart_view AS
SELECT 
    orders.id AS order_id,
    orders.user_id,
    orders.firstName,
    orders.lastName,
    orders.email,
    orders.phone,
    orders.company,
    orders.country,
    orders.address,
    orders.address2,
    orders.city,
    orders.state,
    orders.status,
    orders.total,
    orders.paymentMethod,
    orders.Date,
    cart.id AS cart_id,
    cart.name AS product_name,
    cart.price AS product_price,
    cart.quantity AS product_quantity
FROM 
    orders
INNER JOIN 
    cart ON orders.user_id = cart.user_id;












    
      $sql = "SELECT orders.user_id, orders.phone, orders.country, orders.address, orders.address2, orders.city, orders.state, 
              orders.firstName, orders.lastName, cart.name AS product_name, cart.quantity, cart.price AS total_price, 
              orders.status, orders.date, orders.paymentMethod  
              FROM orders 
              JOIN users ON orders.user_id = users.id
              JOIN cart ON orders.user_id = cart.user_id";

