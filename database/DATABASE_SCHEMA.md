# Database Schema Documentation

This document provides an overview of the database schema for the WebDemo e-commerce application.

## Tables

### Users
**Table Name:** `users`
- `id` - Primary key
- `name` - User's name
- `email` - User's email (unique)
- `email_verified_at` - Timestamp when email was verified (nullable)
- `password` - Hashed password
- `remember_token` - Token for "remember me" functionality
- `is_admin` - Boolean flag for admin status (default: false)
- `city` - User's city (nullable)
- `phone` - User's phone number (nullable)
- `gender` - User's gender (nullable)
- `profile_pic` - Path to profile picture (nullable)
- `timestamps` - Created and updated timestamps

### Categories
**Table Name:** `categories`
- `id` - Primary key
- `name` - Category name
- `slug` - URL-friendly name (unique)
- `description` - Category description (nullable)
- `image` - Category image path (nullable)
- `is_active` - Boolean flag for active status (default: true)
- `sort_order` - Integer for sorting (default: 0)
- `timestamps` - Created and updated timestamps

### Collections
**Table Name:** `collections`
- `id` - Primary key
- `title` - Collection title
- `slug` - URL-friendly title (unique)
- `description` - Collection description (nullable)
- `image` - Collection image path (nullable)
- `is_featured` - Boolean flag for featured status (default: false)
- `is_active` - Boolean flag for active status (default: true)
- `sort_order` - Integer for sorting (default: 0)
- `timestamps` - Created and updated timestamps

### Products
**Table Name:** `products`
- `id` - Primary key
- `name` - Product name
- `slug` - URL-friendly name (unique)
- `description` - Product description (nullable)
- `price` - Current price (decimal 10,2)
- `original_price` - Original price before discount (decimal 10,2, nullable)
- `stock` - Available stock quantity (default: 0)
- `image` - Main product image path (nullable)
- `gallery` - JSON array of additional images (nullable)
- `category_id` - Foreign key to categories table
- `badge` - Product badge like 'New', 'Sale', etc. (nullable)
- `is_featured` - Boolean flag for featured status (default: false)
- `is_active` - Boolean flag for active status (default: true)
- `timestamps` - Created and updated timestamps

### Collection Product (Pivot)
**Table Name:** `collection_product`
- `id` - Primary key
- `collection_id` - Foreign key to collections table
- `product_id` - Foreign key to products table
- `timestamps` - Created and updated timestamps

### Carts
**Table Name:** `carts`
- `id` - Primary key
- `user_id` - Foreign key to users table
- `timestamps` - Created and updated timestamps

### Cart Items
**Table Name:** `cart_items`
- `id` - Primary key
- `cart_id` - Foreign key to carts table
- `product_id` - Foreign key to products table
- `quantity` - Quantity of product (default: 1)
- `timestamps` - Created and updated timestamps

### Wishlists
**Table Name:** `wishlists`
- `id` - Primary key
- `user_id` - Foreign key to users table
- `product_id` - Foreign key to products table
- `timestamps` - Created and updated timestamps

### Orders
**Table Name:** `orders`
- `id` - Primary key
- `order_number` - Unique order identifier
- `user_id` - Foreign key to users table
- `subtotal` - Order subtotal (decimal 10,2)
- `tax` - Tax amount (decimal 10,2, default: 0)
- `total` - Order total (decimal 10,2)
- `name` - Customer name
- `email` - Customer email
- `phone` - Customer phone
- `address` - Shipping address
- `country` - Shipping country
- `payment_method` - Method of payment
- `payment_id` - Payment identifier (nullable)
- `status` - Order status (enum: 'pending', 'awaiting_payment', 'processing', 'completed', 'cancelled', default: 'pending')
- `notes` - Order notes (nullable)
- `timestamps` - Created and updated timestamps

### Order Items
**Table Name:** `order_items`
- `id` - Primary key
- `order_id` - Foreign key to orders table
- `product_id` - Foreign key to products table
- `product_name` - Name of product at time of order
- `price` - Price of product at time of order (decimal 10,2)
- `quantity` - Quantity ordered
- `subtotal` - Item subtotal (decimal 10,2)
- `timestamps` - Created and updated timestamps

### Payment Transactions
**Table Name:** `payment_transactions`
- `id` - Primary key
- `order_id` - Foreign key to orders table
- `transaction_id` - External payment transaction ID
- `payment_method` - Method of payment
- `amount` - Transaction amount (decimal 10,2)
- `currency` - Currency code (default: 'INR')
- `status` - Transaction status
- `payment_details` - JSON with additional payment details (nullable)
- `timestamps` - Created and updated timestamps

### Reviews
**Table Name:** `reviews`
- `id` - Primary key
- `user_id` - Foreign key to users table
- `product_id` - Foreign key to products table
- `rating` - Rating from 1 to 5
- `comment` - Review comment (nullable)
- `is_approved` - Boolean flag for approval status (default: true)
- `timestamps` - Created and updated timestamps
- Unique constraint on `user_id` and `product_id` to ensure one review per product per user

### Inquiries
**Table Name:** `inquiries`
- `id` - Primary key
- `name` - Inquirer's name
- `email` - Inquirer's email
- `message` - Inquiry message
- `response` - Response to inquiry (nullable)
- `is_responded` - Boolean flag for response status (default: false)
- `responded_at` - Timestamp when responded (nullable)
- `timestamps` - Created and updated timestamps

### Sliders
**Table Name:** `sliders`
- `id` - Primary key
- `title` - Slider title
- `description` - Slider description (nullable)
- `image` - Slider image path
- `button_text` - Text for call-to-action button (nullable)
- `button_link` - URL for call-to-action button (nullable)
- `is_active` - Boolean flag for active status (default: true)
- `sort_order` - Integer for sorting (default: 0)
- `timestamps` - Created and updated timestamps

### Password Reset Tokens
**Table Name:** `password_reset_tokens`
- Standard Laravel table for password resets

### Failed Jobs
**Table Name:** `failed_jobs`
- Standard Laravel table for failed queue jobs

### Personal Access Tokens
**Table Name:** `personal_access_tokens`
- Standard Laravel table for API authentication

## Relationships

1. **Users**
   - Has many Carts
   - Has many Orders
   - Has many Reviews
   - Has many Wishlists

2. **Categories**
   - Has many Products

3. **Collections**
   - Belongs to many Products (through collection_product)

4. **Products**
   - Belongs to Category
   - Belongs to many Collections (through collection_product)
   - Has many Cart Items
   - Has many Order Items
   - Has many Reviews
   - Has many Wishlists

5. **Carts**
   - Belongs to User
   - Has many Cart Items

6. **Cart Items**
   - Belongs to Cart
   - Belongs to Product

7. **Orders**
   - Belongs to User
   - Has many Order Items
   - Has many Payment Transactions

8. **Order Items**
   - Belongs to Order
   - Belongs to Product

9. **Payment Transactions**
   - Belongs to Order

10. **Reviews**
    - Belongs to User
    - Belongs to Product

11. **Wishlists**
    - Belongs to User
    - Belongs to Product