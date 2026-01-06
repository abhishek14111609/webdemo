# Database Query Examples

## Overview

This document provides examples of common database queries used in the WebDemo e-commerce application. These examples demonstrate how to effectively use Laravel's Eloquent ORM and Query Builder to interact with the database.

## Basic Queries

### Retrieving All Records

```php
// Get all products
$products = App\Models\Product::all();

// Get all active products
$activeProducts = App\Models\Product::where('is_active', true)->get();
```

### Retrieving a Single Record

```php
// Find product by ID
$product = App\Models\Product::find($id);

// Find product by ID with error handling
$product = App\Models\Product::findOrFail($id);

// Find first product that matches criteria
$product = App\Models\Product::where('slug', $slug)->first();

// Find first product that matches criteria with error handling
$product = App\Models\Product::where('slug', $slug)->firstOrFail();
```

### Filtering Records

```php
// Get products in a specific category
$products = App\Models\Product::where('category_id', $categoryId)->get();

// Get products in a price range
$products = App\Models\Product::whereBetween('price', [$minPrice, $maxPrice])->get();

// Get products with specific badges
$products = App\Models\Product::whereIn('badge', ['New', 'Sale'])->get();

// Get products that are in stock and active
$products = App\Models\Product::where('stock', '>', 0)
    ->where('is_active', true)
    ->get();
```

### Ordering Results

```php
// Get products ordered by price (ascending)
$products = App\Models\Product::orderBy('price', 'asc')->get();

// Get products ordered by price (descending)
$products = App\Models\Product::orderBy('price', 'desc')->get();

// Get products ordered by multiple columns
$products = App\Models\Product::orderBy('category_id', 'asc')
    ->orderBy('price', 'desc')
    ->get();
```

### Limiting Results

```php
// Get first 10 products
$products = App\Models\Product::take(10)->get();

// Get 10 products, skipping the first 20
$products = App\Models\Product::skip(20)->take(10)->get();

// Pagination
$products = App\Models\Product::paginate(15);
```

## Relationships

### Eager Loading

```php
// Get products with their categories
$products = App\Models\Product::with('category')->get();

// Get orders with their items and user
$orders = App\Models\Order::with(['items', 'user'])->get();

// Get products with nested relationships
$products = App\Models\Product::with(['category', 'reviews.user'])->get();
```

### Querying Relationship Existence

```php
// Get products that have reviews
$products = App\Models\Product::has('reviews')->get();

// Get products that have at least 3 reviews
$products = App\Models\Product::has('reviews', '>=', 3)->get();

// Get products that have reviews with a rating of 5
$products = App\Models\Product::whereHas('reviews', function ($query) {
    $query->where('rating', 5);
})->get();
```

### Querying Relationship Absence

```php
// Get products that don't have reviews
$products = App\Models\Product::doesntHave('reviews')->get();

// Get products that don't have reviews with a rating of 1
$products = App\Models\Product::whereDoesntHave('reviews', function ($query) {
    $query->where('rating', 1);
})->get();
```

## Aggregates

### Counting

```php
// Count all products
$count = App\Models\Product::count();

// Count products in a category
$count = App\Models\Product::where('category_id', $categoryId)->count();

// Count products with reviews
$count = App\Models\Product::has('reviews')->count();
```

### Sums and Averages

```php
// Get sum of all order totals
$sum = App\Models\Order::sum('total');

// Get average product price
$avg = App\Models\Product::avg('price');

// Get average rating for a product
$avgRating = App\Models\Review::where('product_id', $productId)->avg('rating');
```

### Min and Max

```php
// Get minimum product price
$min = App\Models\Product::min('price');

// Get maximum product price
$max = App\Models\Product::max('price');
```

## Advanced Queries

### Raw Expressions

```php
// Using raw expressions in select
$products = App\Models\Product::select(DB::raw('*, price * 0.9 as sale_price'))->get();

// Using raw expressions in where
$products = App\Models\Product::whereRaw('price > ? and stock > ?', [$minPrice, $minStock])->get();
```

### Joins

```php
// Join products and categories
$products = DB::table('products')
    ->join('categories', 'products.category_id', '=', 'categories.id')
    ->select('products.*', 'categories.name as category_name')
    ->get();
```

### Subqueries

```php
// Get products with above-average price
$avgPrice = App\Models\Product::avg('price');
$products = App\Models\Product::where('price', '>', $avgPrice)->get();

// Using subquery in where
$products = App\Models\Product::where('price', '>', function ($query) {
    $query->from('products')->selectRaw('AVG(price)');
})->get();
```

### Conditional Clauses

```php
// Conditional where clauses
$query = App\Models\Product::query();

if ($request->has('category_id')) {
    $query->where('category_id', $request->category_id);
}

if ($request->has('min_price')) {
    $query->where('price', '>=', $request->min_price);
}

if ($request->has('max_price')) {
    $query->where('price', '<=', $request->max_price);
}

$products = $query->get();
```

## Transactions

```php
// Using transactions for order creation
DB::transaction(function () use ($cart, $user, $request) {
    // Create order
    $order = App\Models\Order::create([
        'order_number' => uniqid(),
        'user_id' => $user->id,
        'subtotal' => $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        }),
        'tax' => 0,
        'total' => $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        }),
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'country' => $request->country,
        'payment_method' => $request->payment_method,
        'status' => 'pending',
    ]);
    
    // Create order items
    foreach ($cart->items as $item) {
        App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'product_name' => $item->product->name,
            'price' => $item->product->price,
            'quantity' => $item->quantity,
            'subtotal' => $item->product->price * $item->quantity,
        ]);
        
        // Update product stock
        $item->product->decrement('stock', $item->quantity);
    }
    
    // Clear cart
    $cart->items()->delete();
    
    return $order;
});
```

## Eloquent Collections

```php
// Filter collection
$inStockProducts = $products->filter(function ($product) {
    return $product->stock > 0;
});

// Map collection
$productNames = $products->map(function ($product) {
    return $product->name;
});

// Pluck specific column
$productIds = $products->pluck('id');

// Group by category
$productsByCategory = $products->groupBy('category_id');
```

## Query Scopes

```php
// Using query scopes defined in models
$featuredProducts = App\Models\Product::featured()->active()->get();
$pendingOrders = App\Models\Order::pending()->latest()->get();
$topRatedProducts = App\Models\Product::withAvgRating()->orderByDesc('avg_rating')->take(10)->get();
```

## Search Functionality

```php
// Basic search
$searchTerm = $request->search;
$products = App\Models\Product::where('name', 'like', "%{$searchTerm}%")
    ->orWhere('description', 'like', "%{$searchTerm}%")
    ->get();

// Advanced search with multiple fields
$searchTerm = $request->search;
$products = App\Models\Product::where(function ($query) use ($searchTerm) {
    $query->where('name', 'like', "%{$searchTerm}%")
        ->orWhere('description', 'like', "%{$searchTerm}%");
})
->when($request->has('category_id'), function ($query) use ($request) {
    return $query->where('category_id', $request->category_id);
})
->when($request->has('min_price'), function ($query) use ($request) {
    return $query->where('price', '>=', $request->min_price);
})
->when($request->has('max_price'), function ($query) use ($request) {
    return $query->where('price', '<=', $request->max_price);
})
->get();
```

## Conclusion

These examples demonstrate common database queries used in the WebDemo e-commerce application. Laravel's Eloquent ORM and Query Builder provide powerful tools for interacting with the database in a clean and expressive way.

For more information, refer to the [Laravel documentation](https://laravel.com/docs/database).