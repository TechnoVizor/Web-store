<?php

namespace Tests\Feature;

use App\Livewire\Checkout;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_place_order_from_session_cart(): void
    {
        $user = User::factory()->create([
            'phone' => '+371 20000000',
            'address' => 'Riga Test 1',
        ]);

        $product = Product::factory()->create([
            'is_active' => true,
            'price' => 39,
        ]);

        $this->actingAs($user)
            ->withSession([
                'cart' => [
                    $product->id => [
                        'name' => $product->name,
                        'quantity' => 1,
                        'price' => $product->price,
                        'image' => $product->image_url,
                    ],
                ],
            ]);

        Livewire::test(Checkout::class)
            ->set('name', 'Test Buyer')
            ->set('email', 'buyer@example.com')
            ->set('phone', '+371 20000000')
            ->set('address', 'Riga Test 1')
            ->call('placeOrder')
            ->assertHasNoErrors()
            ->assertRedirect(route('orders.success'));

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'customer_email' => 'buyer@example.com',
            'total_amount' => 39,
        ]);

        $this->assertSame(1, Order::query()->first()->items()->count());
    }

    public function test_guest_can_place_order_from_session_cart(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'price' => 79,
        ]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'name' => $product->name,
                    'quantity' => 2,
                    'price' => $product->price,
                    'image' => $product->image_url,
                ],
            ],
        ]);

        Livewire::test(Checkout::class)
            ->set('name', 'Guest Buyer')
            ->set('email', 'guest@example.com')
            ->set('phone', '+371 20000001')
            ->set('address', 'Riga Guest Street 2')
            ->call('placeOrder')
            ->assertHasNoErrors()
            ->assertRedirect(route('orders.success'));

        $this->assertDatabaseHas('orders', [
            'user_id' => null,
            'customer_email' => 'guest@example.com',
            'total_amount' => 158,
        ]);
    }

    public function test_guest_can_open_cart_and_checkout_pages(): void
    {
        $this->get(route('cart.index'))->assertOk();
        $this->get(route('checkout.index'))->assertOk();
        $this->get(route('orders.success'))->assertOk();
    }
}
