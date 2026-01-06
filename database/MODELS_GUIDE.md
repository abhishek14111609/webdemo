# Database Models Guide

## Overview

This guide documents the Eloquent models used in the WebDemo e-commerce application and their relationships. Understanding these models and relationships is essential for working with the application's data layer.

## Models and Relationships

### User Model

**File:** `app/Models/User.php`

**Relationships:**
- Has one Cart
- Has many Orders
- Has many Reviews
- Has many Wishlists

**Example Usage:**
```php
// Get user's cart
$cart = $user->cart;

// Get user's orders
$orders = $user->orders;

// Get user's reviews
$reviews = $user->reviews;

// Check if user is admin
if ($user->is_admin) {
    // Admin-specific logic
}
```

### Category Model

**File:** `app/Models/Category.php`

**Relationships:**
- Has many Products

**Example Usage:**
```php
// Get all products in a category
$products = $category->products;

// Get active products in a category
$activeProducts = $category->products()->where('is_active', true)->get();

// Get category with its products
$categoryWithProducts = Category::with('products')->find($id);
```

### Collection Model

**File:** `app/Models/Collection.php`

**Relationships:**
- Belongs to many Products

**Example Usage:**
```php
// Get all products in a collection
$products = $collection->products;

// Add a product to a collection
$collection->products()->attach($productId);

// Remove a product from a collection
$collection->products()->detach($productId);

// Get featured collections with their products
$featuredCollections = Collection::with('products')
    ->where('is_featured', true)
    ->where('is_active', true)
    ->get();
```

### Product Model

**File:** `app/Models/Product.php`

**Relationships:**
- Belongs to Category
- Belongs to many Collections
- Has many Reviews
- Has many OrderItems
- Has many CartItems
- Has many Wishlists

**Example Usage:**
```php
// Get product's category
$category = $product->category;

// Get product's collections
$collections = $product->collections;

// Get product's reviews
$reviews = $product->reviews;

// Get average rating
$averageRating = $product->reviews()->avg('rating');

// Check if product is in stock
if ($product->stock > 0) {
    // In-stock logic
}

// Get featured products
$featuredProducts = Product::where('is_featured', true)
    ->where('is_active', true)
    ->get();
```

### Cart Model

**File:** `app/Models/Cart.php`

**Relationships:**
- Belongs to User
- Has many CartItems

**Example Usage:**
```php
// Get cart's user
$user = $cart->user;

// Get cart items
$cartItems = $cart->items;

// Calculate cart total
$total = $cart->items->sum(function ($item) {
    return $item->product->price * $item->quantity;
});

// Add item to cart
$cart->items()->create([
    'product_id' => $productId,
    'quantity' => $quantity
]);
```

### CartItem Model

**File:** `app/Models/CartItem.php`

**Relationships:**
- Belongs to Cart
- Belongs to Product

**Example Usage:**
```php
// Get cart item's cart
$cart = $cartItem->cart;

// Get cart item's product
$product = $cartItem->product;

// Calculate subtotal
$subtotal = $cartItem->product->price * $cartItem->quantity;

// Update quantity
$cartItem->update(['quantity' => $newQuantity]);
```

### Order Model

**File:** `app/Models/Order.php`

**Relationships:**
- Belongs to User
- Has many OrderItems
- Has many PaymentTransactions

**Example Usage:**
```php
// Get order's user
$user = $order->user;

// Get order items
$orderItems = $order->items;

// Get payment transactions
$transactions = $order->paymentTransactions;

// Check order status
if ($order->status === 'completed') {
    // Completed order logic
}

// Get recent orders
$recentOrders = Order::with('items')
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();
```

### OrderItem Model

**File:** `app/Models/OrderItem.php`

**Relationships:**
- Belongs to Order
- Belongs to Product

**Example Usage:**
```php
// Get order item's order
$order = $orderItem->order;

// Get order item's product
$product = $orderItem->product;

// Calculate subtotal
$subtotal = $orderItem->price * $orderItem->quantity;
```

### PaymentTransaction Model

**File:** `app/Models/PaymentTransaction.php`

**Relationships:**
- Belongs to Order

**Example Usage:**
```php
// Get transaction's order
$order = $transaction->order;

// Get payment details
$paymentDetails = json_decode($transaction->payment_details, true);

// Check transaction status
if ($transaction->status === 'completed') {
    // Completed transaction logic
}
```

### Review Model

**File:** `app/Models/Review.php`

**Relationships:**
- Belongs to User
- Belongs to Product

**Example Usage:**
```php
// Get review's user
$user = $review->user;

// Get review's product
$product = $review->product;

// Get approved reviews for a product
$approvedReviews = Review::where('product_id', $productId)
    ->where('is_approved', true)
    ->orderBy('created_at', 'desc')
    ->get();
```

### Wishlist Model

**File:** `app/Models/Wishlist.php`

**Relationships:**
- Belongs to User
- Belongs to Product

**Example Usage:**
```php
// Get wishlist's user
$user = $wishlist->user;

// Get wishlist's product
$product = $wishlist->product;

// Check if product is in user's wishlist
$isInWishlist = Wishlist::where('user_id', $userId)
    ->where('product_id', $productId)
    ->exists();

// Add product to wishlist
Wishlist::create([
    'user_id' => $userId,
    'product_id' => $productId
]);
```

### Inquiry Model

**File:** `app/Models/Inquiry.php`

**Example Usage:**
```php
// Mark inquiry as responded
$inquiry->update([
    'response' => $responseText,
    'is_responded' => true,
    'responded_at' => now()
]);

// Get unresponded inquiries
$pendingInquiries = Inquiry::where('is_responded', false)
    ->orderBy('created_at', 'asc')
    ->get();
```

### Slider Model

**File:** `app/Models/Slider.php`

**Example Usage:**
```php
// Get active sliders
$activeSliders = Slider::where('is_active', true)
    ->orderBy('sort_order', 'asc')
    ->get();
```

## Model Scopes

Many models may include query scopes to simplify common queries:

```php
// Example of model scopes in Product model
class Product extends Model
{
    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Scope for featured products
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    // Scope for in-stock products
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}

// Usage of scopes
$products = Product::active()->featured()->inStock()->get();
```

## Accessors and Mutators

Models may include accessors and mutators to transform attributes:

```php
// Example of accessors and mutators in Product model
class Product extends Model
{
    // Format price for display
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }
    
    // Calculate discount percentage
    public function getDiscountPercentageAttribute()
    {
        if (!$this->original_price) {
            return 0;
        }
        
        return round((($this->original_price - $this->price) / $this->original_price) * 100);
    }
}

// Usage of accessors
echo $product->formatted_price; // $99.99
echo $product->discount_percentage; // 20
```

## Conclusion

Understanding the models and their relationships is crucial for effectively working with the WebDemo e-commerce application's data layer. This guide provides a reference for the most common operations, but you should also refer to the actual model files for the most up-to-date implementations.