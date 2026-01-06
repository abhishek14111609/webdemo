<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return bool
     */
    public function view(?User $user, Cart $cart): bool
    {
        // Allow guests to view their own session-based cart
        if (!$user) {
            return session()->get('cart_id') === $cart->id;
        }
        // Authenticated users can only view their own cart
        return $user->id === $cart->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cart  $cart
     * @return bool
     */
    public function update(?User $user, Cart $cart): bool
    {
        if (!$user) {
            return session()->get('cart_id') === $cart->id;
        }
        return $user->id === $cart->user_id;
    }
}
