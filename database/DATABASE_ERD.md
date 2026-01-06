# Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS ||--o{ CARTS : has
    USERS ||--o{ ORDERS : places
    USERS ||--o{ REVIEWS : writes
    USERS ||--o{ WISHLISTS : has
    
    CATEGORIES ||--o{ PRODUCTS : contains
    
    COLLECTIONS }o--o{ PRODUCTS : contains
    COLLECTION_PRODUCT }|--|| COLLECTIONS : belongs_to
    COLLECTION_PRODUCT }|--|| PRODUCTS : belongs_to
    
    CARTS ||--o{ CART_ITEMS : contains
    CART_ITEMS }|--|| PRODUCTS : references
    
    ORDERS ||--o{ ORDER_ITEMS : contains
    ORDERS ||--o{ PAYMENT_TRANSACTIONS : has
    ORDER_ITEMS }|--|| PRODUCTS : references
    
    PRODUCTS ||--o{ REVIEWS : receives
    PRODUCTS ||--o{ WISHLISTS : in
    
    USERS {
        id int PK
        name string
        email string UK
        email_verified_at timestamp
        password string
        remember_token string
        is_admin boolean
        city string
        phone string
        gender string
        profile_pic string
        created_at timestamp
        updated_at timestamp
    }
    
    CATEGORIES {
        id int PK
        name string
        slug string UK
        description text
        image string
        is_active boolean
        sort_order int
        created_at timestamp
        updated_at timestamp
    }
    
    COLLECTIONS {
        id int PK
        title string
        slug string UK
        description text
        image string
        is_featured boolean
        is_active boolean
        sort_order int
        created_at timestamp
        updated_at timestamp
    }
    
    PRODUCTS {
        id int PK
        name string
        slug string UK
        description text
        price decimal
        original_price decimal
        stock int
        image string
        gallery json
        category_id int FK
        badge string
        is_featured boolean
        is_active boolean
        created_at timestamp
        updated_at timestamp
    }
    
    COLLECTION_PRODUCT {
        id int PK
        collection_id int FK
        product_id int FK
        created_at timestamp
        updated_at timestamp
    }
    
    CARTS {
        id int PK
        user_id int FK
        created_at timestamp
        updated_at timestamp
    }
    
    CART_ITEMS {
        id int PK
        cart_id int FK
        product_id int FK
        quantity int
        created_at timestamp
        updated_at timestamp
    }
    
    WISHLISTS {
        id int PK
        user_id int FK
        product_id int FK
        created_at timestamp
        updated_at timestamp
    }
    
    ORDERS {
        id int PK
        order_number string UK
        user_id int FK
        subtotal decimal
        tax decimal
        total decimal
        name string
        email string
        phone string
        address text
        country string
        payment_method string
        payment_id string
        status enum
        notes text
        created_at timestamp
        updated_at timestamp
    }
    
    ORDER_ITEMS {
        id int PK
        order_id int FK
        product_id int FK
        product_name string
        price decimal
        quantity int
        subtotal decimal
        created_at timestamp
        updated_at timestamp
    }
    
    PAYMENT_TRANSACTIONS {
        id int PK
        order_id int FK
        transaction_id string
        payment_method string
        amount decimal
        currency string
        status string
        payment_details json
        created_at timestamp
        updated_at timestamp
    }
    
    REVIEWS {
        id int PK
        user_id int FK
        product_id int FK
        rating int
        comment text
        is_approved boolean
        created_at timestamp
        updated_at timestamp
    }
    
    INQUIRIES {
        id int PK
        name string
        email string
        message text
        response text
        is_responded boolean
        responded_at timestamp
        created_at timestamp
        updated_at timestamp
    }
    
    SLIDERS {
        id int PK
        title string
        description text
        image string
        button_text string
        button_link string
        is_active boolean
        sort_order int
        created_at timestamp
        updated_at timestamp
    }
```

## Notes

- **PK**: Primary Key
- **FK**: Foreign Key
- **UK**: Unique Key
- Relationship notations:
  - `||--o{`: One-to-many relationship
  - `}o--o{`: Many-to-many relationship
  - `}|--||`: Many-to-one relationship

## Standard Laravel Tables (not shown in diagram)

- `password_reset_tokens`
- `failed_jobs`
- `personal_access_tokens`