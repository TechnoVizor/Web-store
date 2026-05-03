<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class Checkout extends Component
{
    public $name = '';
    public $email = ''; // ДОБАВЬ ЭТУ СТРОКУ
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
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        DB::transaction(function () use ($cart, $total) {
            $order = Order::create([
                'user_id' => Auth::id() ?? 1,
                'customer_name' => $this->name,
                'customer_email' => $this->email, // Проверь, есть ли такая колонка в БД!
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

    } catch (\Exception $e) {
        // Это выведет реальную ошибку (например, "Column not found") прямо на экран
        dd($e->getMessage()); 
    }
}

    public function render()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // Просто возвращаем вьюху, БЕЗ ->layout() и ->section()
        return view('livewire.checkout', [
            'cart' => $cart,
            'total' => $total
        ])->layout('layouts.app');
}

    protected $messages = [
        'name.required' => 'Идентификатор пользователя не обнаружен.',
        'email.required' => 'Канал связи (Email) обязателен.',
        'phone.required' => 'Требуется защищенная линия связи (Телефон).',
        'phone.regex' => 'Неверный протокол записи номера.',
        'address.required' => 'Координаты точки сброса (Адрес) не указаны.',
        'address.min' => 'Адрес слишком короткий для точного наведения курьера.',
    ];

    private function calculateTotal()
    {
        $this->total = collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }
}