<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CartItem  $cartItem
     * @return bool
     */
    public function update(?User $user, CartItem $cartItem): bool
    {
        if (!$user) {
            return session()->get('cart_id') === $cartItem->cart_id;
        }
        return $user->id === $cartItem->cart->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CartItem  $cartItem
     * @return bool
     */
    public function delete(?User $user, CartItem $cartItem): bool
    {
        if (!$user) {
            return session()->get('cart_id') === $cartItem->cart_id;
        }
        return $user->id === $cartItem->cart->user_id;
    }
}
