<?php

namespace Tests\Feature;

use App\Livewire\Checkout;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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
            'customer_phone_normalized' => '37120000001',
            'total_amount' => 158,
        ]);
    }

    public function test_guest_can_place_order_with_phone_without_email(): void
    {
        $product = Product::factory()->create([
            'is_active' => true,
            'price' => 59,
        ]);

        $this->withSession([
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
            ->set('name', 'Phone Buyer')
            ->set('email', '')
            ->set('phone', '+371 (20) 000-002')
            ->set('address', 'Riga Phone Street 3')
            ->call('placeOrder')
            ->assertHasNoErrors()
            ->assertRedirect(route('orders.success'));

        $this->assertDatabaseHas('orders', [
            'user_id' => null,
            'customer_email' => '',
            'customer_phone_normalized' => '37120000002',
            'total_amount' => 59,
        ]);
    }

    public function test_phone_registration_claims_previous_guest_orders(): void
    {
        Order::create([
            'user_id' => null,
            'customer_name' => 'Future Customer',
            'customer_phone' => '+371 2000 0003',
            'customer_phone_normalized' => '37120000003',
            'status' => 'new',
            'total_amount' => 120,
        ]);

        Livewire::test('auth.auth-form')
            ->set('name', 'Future Customer')
            ->set('nickname', 'future_customer')
            ->set('phone', '+371 2000 0003')
            ->set('password', 'password123')
            ->call('register')
            ->assertRedirect('/');

        $user = User::where('phone_normalized', '37120000003')->firstOrFail();

        $this->assertTrue(Auth::check());
        $this->assertDatabaseHas('orders', [
            'customer_phone_normalized' => '37120000003',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_login_with_phone_number(): void
    {
        $user = User::factory()->create([
            'phone' => '+371 2000 0004',
            'password' => 'password123',
        ]);

        Livewire::test('auth.auth-form')
            ->set('loginIdentifier', '+371 2000 0004')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_guest_can_open_cart_and_checkout_pages(): void
    {
        $this->get(route('cart.index'))->assertOk();
        $this->get(route('checkout.index'))->assertOk();
        $this->get(route('orders.success'))->assertOk();
    }
}
