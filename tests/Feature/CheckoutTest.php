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
}
