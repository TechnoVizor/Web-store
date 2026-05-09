<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Support\Phone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

#[Layout('layouts.app')]
class Checkout extends Component
{
    public $name = '';

    public $email = '';

    public $phone = '';

    public $address = '';

    public $cart = [];

    public $total = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'required|string',
        'address' => 'required|string',
    ];

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            // Предполагаем, что поле phone есть в таблице users
            $this->phone = $user->phone;
            $this->address = $user->address ?? '';
        }

        $this->cart = session()->get('cart', []);
        $this->calculateTotal();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:2',
            'email' => 'nullable|email',
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'address' => 'required|string|min:5',
        ];
    }

    public function placeOrder()
    {
        try {
            $this->validate();

            $cart = session()->get('cart', []);
            if (empty($cart)) {
                $this->addError('checkout', __('ui.checkout.empty_cart'));

                return;
            }

            $cartItems = collect($cart)
                ->map(function ($item, $id) {
                    if (! ctype_digit((string) $id)) {
                        return null;
                    }

                    return [
                        'product_id' => (int) $id,
                        'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                    ];
                })
                ->filter()
                ->values();

            if ($cartItems->isEmpty() || $cartItems->count() !== count($cart)) {
                $this->addError('checkout', __('ui.checkout.item_unavailable'));

                return;
            }

            $productIds = $cartItems->pluck('product_id')->unique()->values();
            $products = Product::query()
                ->whereIn('id', $productIds)
                ->where('is_active', true)
                ->get(['id', 'price'])
                ->keyBy('id');

            if ($products->count() !== $productIds->count()) {
                $this->addError('checkout', __('ui.checkout.item_unavailable'));

                return;
            }

            $items = $cartItems
                ->map(function ($item) use ($products) {
                    return [
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $products[$item['product_id']]->price,
                    ];
                })
                ->values();

            $total = $items->sum(fn ($item) => $item['price'] * $item['quantity']);

            if (! app()->runningUnitTests()) {
                DB::purge();
                DB::reconnect();
            }

            $order = null;

            try {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $this->name,
                    'customer_email' => $this->email,
                    'customer_phone' => $this->phone,
                    'customer_phone_normalized' => Phone::normalize($this->phone),
                    'customer_address' => $this->address,
                    'status' => 'new',
                    'total_amount' => $total,
                ]);

                $items->each(function ($item) use ($order) {
                    try {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                        ]);
                    } catch (Throwable $e) {
                        Log::error('Checkout item insert failed', [
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'message' => $e->getMessage(),
                            'previous' => $e->getPrevious()?->getMessage(),
                        ]);

                        throw $e;
                    }
                });
            } catch (Throwable $e) {
                if ($order?->exists) {
                    try {
                        $order->delete();
                    } catch (Throwable $cleanupException) {
                        Log::warning('Checkout cleanup failed', [
                            'order_id' => $order->id,
                            'message' => $cleanupException->getMessage(),
                        ]);
                    }
                }

                throw $e;
            }

            session()->forget('cart');

            return redirect()->route('orders.success');

        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Checkout failed', [
                'user_id' => Auth::id(),
                'cart_product_ids' => collect(session()->get('cart', []))->keys()->values()->all(),
                'message' => $e->getMessage(),
                'previous' => $e->getPrevious()?->getMessage(),
            ]);

            $this->addError('checkout', __('ui.checkout.failed'));
        }
    }

    public function render()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));

        // Просто возвращаем вьюху, БЕЗ ->layout() и ->section()
        return view('livewire.checkout', [
            'cart' => $cart,
            'total' => $total,
        ])->layout('layouts.app');
    }

    protected $messages = [
        'name.required' => 'ui.checkout.validation.name_required',
        'email.email' => 'ui.checkout.validation.email_valid',
        'phone.required' => 'ui.checkout.validation.phone_required',
        'phone.regex' => 'ui.checkout.validation.phone_valid',
        'phone.min' => 'ui.checkout.validation.phone_min',
        'address.required' => 'ui.checkout.validation.address_required',
        'address.min' => 'ui.checkout.validation.address_min',
    ];

    protected function messages()
    {
        return collect($this->messages)
            ->map(fn ($key) => __($key))
            ->all();
    }

    private function calculateTotal()
    {
        $this->total = collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }
}
