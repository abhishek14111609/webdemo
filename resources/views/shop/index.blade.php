@extends('layouts.master')

@section('title', $title . ' | ' . config('app.name'))

@push('styles')
<style>
.shop-container { padding: 2rem 0; }
.filter-sidebar { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 2px 15px rgba(0,0,0,0.05); margin-bottom: 2rem; }
.filter-header { font-weight: 600; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #eee; font-size: 1.1rem; }
.filter-group { margin-bottom: 1.5rem; }
.filter-group h5 { font-size: 0.95rem; font-weight: 600; margin-bottom: 0.75rem; color: #333; }
.form-check { margin-bottom: 0.5rem; }
.form-check-label { cursor: pointer; font-size: 0.9rem; color: #555; }
.form-check-input:checked + .form-check-label { color: #000; font-weight: 500; }
.product-card { border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; }
.product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
.product-image { position: relative; overflow: hidden; padding-top: 100%; background: #f9f9f9; }
.product-image img { position: absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; transition: transform 0.5s ease; }
.product-card:hover .product-image img { transform: scale(1.05); }
.product-badge { position: absolute; top:10px; right:10px; background:#d4af37; color:white; padding:0.25rem 0.75rem; border-radius:20px; font-size:0.75rem; font-weight:600; }
.product-details { padding: 1.25rem; flex-grow:1; display:flex; flex-direction:column; }
.product-category { font-size:0.8rem; color:#888; margin-bottom:0.25rem; text-transform:uppercase; letter-spacing:0.5px; }
.product-title { font-size:1rem; font-weight:600; margin-bottom:0.5rem; color:#333; line-height:1.4; }
.product-price { font-size:1.1rem; font-weight:700; color:#222; margin:0.5rem 0; }
.product-price .original { text-decoration: line-through; color:#999; font-size:0.9rem; margin-right:0.5rem; }
.product-actions { margin-top:auto; padding-top:1rem; display:flex; gap:0.5rem; }
.btn-add-to-cart { flex:1; background:#000; color:white; border:none; padding:0.5rem; border-radius:4px; font-size:0.85rem; font-weight:500; transition:all 0.3s ease; }
.btn-add-to-cart:hover { background:#333; }
.btn-wishlist { width:40px; height:40px; display:flex; align-items:center; justify-content:center; background:#f8f9fa; border:1px solid #eee; border-radius:4px; color:#666; transition:all 0.3s ease; }
.btn-wishlist:hover { color:#d4af37; border-color:#d4af37; }
.shop-header { margin-bottom:2rem; padding-bottom:1rem; border-bottom:1px solid #eee; }
.shop-title { font-size:1.75rem; font-weight:700; color:#222; margin-bottom:0.5rem; }
.shop-description { color:#666; font-size:1rem; max-width:700px; }
.sorting-options { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; }
.sorting-label { font-size:0.9rem; color:#666; white-space:nowrap; }
.form-select { border-radius:4px; padding:0.5rem 2rem 0.5rem 1rem; border-color:#ddd; font-size:0.9rem; cursor:pointer; }
.pagination { margin-top:3rem; justify-content:center; }
.page-link { color:#333; border:1px solid #eee; padding:0.5rem 1rem; margin:0 0.25rem; border-radius:4px; }
.page-item.active .page-link { background-color:#d4af37; border-color:#d4af37; }
.no-products { text-align:center; padding:4rem 0; color:#666; }

@media (max-width:991.98px) {
    .filter-sidebar { margin-bottom:2rem; }
    .shop-header { text-align:center; }
    .sorting-options { justify-content:center; }
}
@media (max-width:767.98px) {
    .product-card { max-width:280px; margin:0 auto 2rem; }
    .shop-title { font-size:1.5rem; }
}
</style>
@endpush

@section('content')
<div class="shop-container">
    <div class="container">
        <!-- Shop Header -->
        <div class="shop-header">
            <h1 class="shop-title">{{ $title }}</h1>
            <p class="shop-description">{{ $description }}</p>
        </div>

        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <div class="filter-header">Filters</div>

                    <!-- Search Form -->
                    <div class="filter-group">
                        <h5>Search Products</h5>
                        <form action="{{ route('shop') }}" method="GET" id="search-form">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search..." name="search" value="{{ request('search') }}">
                                <button class="btn btn-dark" type="submit">Search</button>
                            </div>
                            @if(request()->has('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request()->has('collection')) <input type="hidden" name="collection" value="{{ request('collection') }}"> @endif
                            @if(request()->has('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                        </form>
                    </div>

                    <!-- Categories Filter -->
                    <div class="filter-group">
                        <h5>Categories</h5>
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="checkbox" value="" id="allJewelry" {{ !request()->has('category') ? 'checked' : '' }}>
                            <label class="form-check-label" for="allJewelry">All Jewelry</label>
                        </div>
                        @foreach($categories as $category)
                        <div class="form-check">
                            <input class="form-check-input category-filter" type="checkbox" value="{{ $category->slug }}" id="category-{{ $category->id }}" {{ request('category') == $category->slug ? 'checked' : '' }}>
                            <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                        @endforeach
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <h5>Price Range</h5>
                        <input type="range" class="form-range" id="priceRange" min="0" max="5000" step="100" value="{{ request('max_price', 5000) }}">
                        <div class="d-flex justify-content-between mt-2">
                            <span>$<span id="minPriceValue">{{ request('min_price', 0) }}</span></span>
                            <span>$<span id="maxPriceValue">{{ request('max_price', 5000) }}</span></span>
                        </div>
                        <input type="hidden" id="min_price" name="min_price" value="{{ request('min_price', 0) }}">
                        <input type="hidden" id="max_price" name="max_price" value="{{ request('max_price', 5000) }}">
                    </div>

                    <button class="btn btn-dark w-100 mt-3" id="applyFilters">Apply Filters</button>
                    <button class="btn btn-outline-secondary w-100 mt-2" id="resetFilters">Reset All</button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="sorting-options">
                        <span class="sorting-label">Sort by:</span>
                        <select class="form-select" id="sortBy" style="width:auto;">
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                    <span class="text-muted d-none d-md-block">{{ $products->total() }} products</span>
                </div>

                <div class="row" id="productsGrid">
                    @forelse($products as $product)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300?text=No+Image' }}" alt="{{ $product->name }}">
                                @if($product->badge)<span class="product-badge">{{ $product->badge }}</span>@endif
                            </div>
                            <div class="product-details">
                                <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                                <h3 class="product-title">{{ $product->name }}</h3>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="star-rating">
                                        @for($i=1;$i<=5;$i++)
                                            @if($i <= round($product->average_rating))
                                                <i class="fas fa-star text-warning" style="font-size:0.8rem;"></i>
                                            @else
                                                <i class="far fa-star text-muted" style="font-size:0.8rem;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-muted ms-1" style="font-size:0.7rem;">({{ $product->reviews_count }})</span>
                                </div>
                                <div class="product-price">
                                    @if($product->original_price && $product->original_price > $product->price)
                                        <span class="original">${{ number_format($product->original_price,2) }}</span>
                                    @endif
                                    ${{ number_format($product->price,2) }}
                                </div>
                                <div class="product-actions">
                                    <form action="{{ route('cart.add',['product'=>$product->id]) }}" method="POST" class="d-inline-block me-2">@csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-to-cart"><i class="fas fa-shopping-cart me-1"></i>Add to Cart</button>
                                    </form>
                                    <form action="{{ route('wishlist.add',['product'=>$product->id]) }}" method="POST" class="d-inline-block">@csrf
                                        <button type="submit" class="btn-wishlist" title="Add to Wishlist"><i class="far fa-heart"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <h3>No products found</h3>
                        <p>Try adjusting your filters or check back later for new arrivals.</p>
                    </div>
                    @endforelse
                </div>

                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceRange = document.getElementById('priceRange');
    const maxPriceValue = document.getElementById('maxPriceValue');
    const minPriceInput = document.getElementById('min_price');
    const maxPriceInput = document.getElementById('max_price');
    if(priceRange){
        priceRange.addEventListener('input', function(){
            maxPriceValue.textContent = this.value;
            maxPriceInput.value = this.value;
        });
    }

    const categoryFilters = document.querySelectorAll('.category-filter');
    categoryFilters.forEach(filter=>{
        filter.addEventListener('change',function(){
            if(this.id==='allJewelry' && this.checked){
                categoryFilters.forEach(f=>{if(f.id!=='allJewelry') f.checked=false;});
            } else if(this.checked){
                document.getElementById('allJewelry').checked=false;
            }
        });
    });

    document.getElementById('applyFilters')?.addEventListener('click',function(){
        const form = document.createElement('form'); form.method='GET'; form.action='{{ route("shop") }}';
        const searchVal = document.querySelector('input[name="search"]').value; if(searchVal){ let s=document.createElement('input'); s.type='hidden'; s.name='search'; s.value=searchVal; form.appendChild(s);}
        categoryFilters.forEach(f=>{if(f.checked && f.id!=='allJewelry'){ let c=document.createElement('input'); c.type='hidden'; c.name='category'; c.value=f.value; form.appendChild(c);}});
        let min=document.createElement('input'); min.type='hidden'; min.name='min_price'; min.value=minPriceInput.value; form.appendChild(min);
        let max=document.createElement('input'); max.type='hidden'; max.name='max_price'; max.value=maxPriceInput.value; form.appendChild(max);
        let sort=document.getElementById('sortBy'); if(sort){ let s=document.createElement('input'); s.type='hidden'; s.name='sort'; s.value=sort.value; form.appendChild(s);}
        document.body.appendChild(form); form.submit();
    });

    document.getElementById('resetFilters')?.addEventListener('click',()=>window.location.href='{{ route("shop") }}');

    document.getElementById('sortBy')?.addEventListener('change',function(){
        const form=document.createElement('form'); form.method='GET'; form.action='{{ route("shop") }}';
        new URLSearchParams(window.location.search).forEach((v,k)=>{if(k!=='sort' && k!=='page'){ let i=document.createElement('input'); i.type='hidden'; i.name=k; i.value=v; form.appendChild(i);}});
        let sortParam=document.createElement('input'); sortParam.type='hidden'; sortParam.name='sort'; sortParam.value=this.value; form.appendChild(sortParam);
        document.body.appendChild(form); form.submit();
    });
});
</script>
@endpush
