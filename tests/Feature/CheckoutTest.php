<?php

namespace Tests\Feature;

use App\Livewire\Checkout;
use App\Livewire\StoreIndex;
use App\Models\Order;
use App\Models\OrderItem;
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
            ->set('password_confirmation', 'password123')
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

    public function test_registration_requires_matching_password_confirmation(): void
    {
        Livewire::test('auth.auth-form')
            ->set('name', 'Mismatch Customer')
            ->set('nickname', 'mismatch_customer')
            ->set('phone', '+371 2000 0005')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password456')
            ->call('register')
            ->assertHasErrors(['password']);
    }

    public function test_guest_can_open_cart_and_checkout_pages(): void
    {
        $this->get(route('cart.index'))->assertOk();
        $this->get(route('checkout.index'))->assertOk();
        $this->get(route('orders.success'))->assertOk();
    }

    public function test_authenticated_user_can_open_order_details(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'is_active' => true,
            'price' => 45,
            'sizes' => ['M', 'XS', 'XL'],
        ]);
        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '+371 20000009',
            'customer_address' => 'Riga Detail Street 9',
            'status' => 'pending',
            'total_amount' => 45,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 45,
            'size' => 'M',
        ]);

        $this->actingAs($user)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('#'.str_pad($order->id, 5, '0', STR_PAD_LEFT))
            ->assertSee($product->name);
    }

    public function test_store_search_is_case_insensitive(): void
    {
        Product::factory()->create([
            'name' => 'CAP',
            'slug' => 'cap',
            'is_active' => true,
        ]);

        Product::factory()->create([
            'name' => 'Leather Jacket',
            'slug' => 'leather-jacket',
            'is_active' => true,
        ]);

        Livewire::test(StoreIndex::class)
            ->set('search', 'cap')
            ->assertSee('CAP')
            ->assertDontSee('Leather Jacket');
    }

    public function test_store_requires_size_before_adding_to_bag(): void
    {
        $product = Product::factory()->create([
            'name' => 'Sized Hoodie',
            'slug' => 'sized-hoodie',
            'is_active' => true,
            'sizes' => ['S', 'M', 'L'],
        ]);

        Livewire::test(StoreIndex::class)
            ->call('addToBag', $product->id)
            ->assertDispatched('show-system-alert');

        $this->assertSame([], session()->get('cart', []));
    }

    public function test_store_adds_selected_size_to_bag(): void
    {
        $product = Product::factory()->create([
            'name' => 'Sized Jacket',
            'slug' => 'sized-jacket',
            'is_active' => true,
            'price' => 120,
            'sizes' => ['S', 'M', 'L'],
        ]);

        Livewire::test(StoreIndex::class)
            ->set("selectedSizes.{$product->id}", 'M')
            ->call('addToBag', $product->id)
            ->assertDispatched('cart-updated');

        $this->assertSame('M', session("cart.{$product->id}:M.size"));
    }
}
