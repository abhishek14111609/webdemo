# Database Migrations Guide

## Overview

This guide explains how to work with database migrations in the WebDemo e-commerce application. Migrations are a way to version control your database schema and make it easy to share the application's database structure with other developers.

## What are Migrations?

Migrations are like version control for your database, allowing you to modify your database schema in a structured and organized way. Each migration file contains two methods:

- `up()`: Specifies what changes to make to the database when the migration is run
- `down()`: Specifies how to reverse the changes made by the `up()` method

## Existing Migrations

The WebDemo application includes the following migrations:

1. `2014_10_12_000000_create_users_table.php` - Creates the users table
2. `2014_10_12_100000_create_password_reset_tokens_table.php` - Creates the password reset tokens table
3. `2019_08_19_000000_create_failed_jobs_table.php` - Creates the failed jobs table
4. `2019_12_14_000001_create_personal_access_tokens_table.php` - Creates the personal access tokens table
5. `2023_08_01_000000_create_reviews_table.php` - Creates the reviews table
6. `2023_08_02_000000_create_inquiries_table.php` - Creates the inquiries table
7. `2024_01_01_000001_add_profile_fields_to_users_table.php` - Adds profile fields to the users table
8. `2024_01_01_000002_add_is_admin_to_users_table.php` - Adds is_admin field to the users table
9. `2024_06_01_000000_create_orders_table.php` - Creates the orders table
10. `2024_06_01_000001_create_order_items_table.php` - Creates the order items table
11. `2024_06_01_000002_create_payment_transactions_table.php` - Creates the payment transactions table
12. `2025_07_24_122726_add_is_admin_to_users_table.php` - Adds is_admin field to the users table (duplicate)
13. `2025_07_26_113224_create_categories_table.php` - Creates the categories table
14. `2025_07_26_113259_create_collections_table.php` - Creates the collections table
15. `2025_07_26_113311_create_products_table.php` - Creates the products table
16. `2025_07_26_113336_create_collection_product_table.php` - Creates the collection_product pivot table
17. `2025_07_29_111555_create_carts_table.php` - Creates the carts table
18. `2025_07_29_111612_create_wishlists_table.php` - Creates the wishlists table
19. `2025_07_29_112344_create_cart_items_table.php` - Creates the cart items table
20. `2025_07_30_000001_create_sliders_table.php` - Creates the sliders table

## Creating Migrations

To create a new migration, use the Laravel Artisan command:

```bash
php artisan make:migration create_table_name_table
```

For migrations that modify existing tables, use a descriptive name:

```bash
php artisan make:migration add_field_to_table_name_table
```

## Writing Migrations

### Creating a Table

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->integer('stock')->default(0);
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('products');
}
```

### Modifying a Table

```php
public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('image')->nullable()->after('stock');
        $table->boolean('is_featured')->default(false)->after('is_active');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['image', 'is_featured']);
    });
}
```

## Common Column Types

- `$table->id()` - Auto-incrementing primary key
- `$table->string('name')` - VARCHAR column
- `$table->text('description')` - TEXT column
- `$table->integer('count')` - INTEGER column
- `$table->decimal('price', 10, 2)` - DECIMAL column with precision and scale
- `$table->boolean('is_active')` - BOOLEAN column
- `$table->foreignId('user_id')` - Foreign key column
- `$table->timestamp('published_at')` - TIMESTAMP column
- `$table->timestamps()` - Adds created_at and updated_at columns
- `$table->softDeletes()` - Adds deleted_at column for soft deletes
- `$table->enum('status', ['pending', 'active', 'cancelled'])` - ENUM column
- `$table->json('options')` - JSON column

## Column Modifiers

- `->nullable()` - Allow NULL values
- `->default($value)` - Set a default value
- `->unique()` - Add a unique constraint
- `->index()` - Add an index
- `->after('column')` - Specify the column's position
- `->comment('description')` - Add a comment to the column

## Foreign Keys

```php
$table->foreignId('category_id')->constrained();

// With custom table name
$table->foreignId('user_id')->constrained('users');

// With onDelete and onUpdate actions
$table->foreignId('product_id')
    ->constrained()
    ->onDelete('cascade')
    ->onUpdate('cascade');
```

## Running Migrations

To run all pending migrations:

```bash
php artisan migrate
```

To rollback the last batch of migrations:

```bash
php artisan migrate:rollback
```

To rollback all migrations and run them again:

```bash
php artisan migrate:refresh
```

To drop all tables and run all migrations:

```bash
php artisan migrate:fresh
```

## Migration Status

To check the status of migrations:

```bash
php artisan migrate:status
```

## Best Practices

1. **Naming Conventions**:
   - Use descriptive names for migrations
   - Follow the pattern `create_table_name_table` for new tables
   - Follow the pattern `add_field_to_table_name_table` for adding fields

2. **Keep Migrations Small**:
   - Each migration should do one thing
   - Split complex schema changes into multiple migrations

3. **Always Define Down Methods**:
   - Ensure each migration can be rolled back
   - Test both up and down methods

4. **Use Foreign Key Constraints**:
   - Define relationships at the database level
   - Use appropriate onDelete and onUpdate actions

5. **Use Indexes**:
   - Add indexes to columns used in WHERE clauses
   - Add indexes to columns used in JOIN conditions
   - Add indexes to columns used in ORDER BY clauses

6. **Test Migrations**:
   - Test migrations on a development database before deploying
   - Use `migrate:fresh` to test the entire migration process

## Troubleshooting

### Migration Failed

If a migration fails, you can fix the issue and then run:

```bash
php artisan migrate
```

Laravel will only run migrations that haven't been run yet.

### Duplicate Migration

If you have duplicate migrations (like the duplicate `add_is_admin_to_users_table` in this project), you should:

1. Identify which one is needed
2. Remove the duplicate from the migrations directory
3. If the duplicate has already been run, you may need to manually modify the migrations table

### Foreign Key Constraints

If you encounter foreign key constraint errors, ensure that:

1. The referenced table is created before the table with the foreign key
2. The referenced column is the primary key or has a unique constraint

## Conclusion

Migrations are a powerful tool for managing your database schema. By following the guidelines in this document, you can effectively use migrations to maintain and evolve your database structure over time.