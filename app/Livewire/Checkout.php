<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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
            'email' => 'required|email',
            // Валидация телефона: обязателен, минимум 10 цифр
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

            $productIds = array_map('intval', array_keys($cart));
            $availableProducts = Product::query()
                ->whereIn('id', $productIds)
                ->where('is_active', true)
                ->pluck('id')
                ->all();

            if (count($availableProducts) !== count($productIds)) {
                $this->addError('checkout', __('ui.checkout.item_unavailable'));

                return;
            }

            $total = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));

            DB::transaction(function () use ($cart, $total) {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $this->name,
                    'customer_email' => $this->email,
                    'customer_phone' => $this->phone,
                    'customer_address' => $this->address,
                    'status' => 'new',
                    'total_amount' => $total,
                ]);

                foreach ($cart as $id => $details) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $id,
                        'quantity' => $details['quantity'],
                        'price' => $details['price'],
                    ]);
                }
            });

            session()->forget('cart');

            return redirect()->route('orders.success');

        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Checkout failed', [
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
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
        'email.required' => 'ui.checkout.validation.email_required',
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
