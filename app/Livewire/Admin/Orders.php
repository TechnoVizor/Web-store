<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Order;


#[Layout('components.layouts.admin')]
class Orders extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $selectedOrder = null;
    public $search = '';

    // Метод для быстрого изменения статуса заказа
    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::findOrFail($orderId);
        $order->update(['status' => $newStatus]);
        
        // Можно добавить уведомление, если нужно
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Ищем по имени клиента или по ID заказа
        $orders = Order::where('customer_name', 'like', '%' . $this->search . '%')
            ->orWhere('id', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.orders', compact('orders'));
    }

    public function deleteOrder($id)
{
    $order = \App\Models\Order::findOrFail($id);

    // Проверка правила: только доставлено или отменено
    if (in_array($order->status, ['delivered', 'cancelled'])) {
        $order->delete();
        // Можно добавить уведомление
        // $this->dispatch('notify', 'Order purged from database.');
    } else {
        // Опционально: сообщение о том, что активные заказы удалять нельзя
        session()->flash('error', 'ACCESS_DENIED: Cannot delete active orders.');
    }
}
    public function openModal($id)
    {
        // ВАЖНО: меняем with('products') на with('items.product')
        $this->selectedOrder = Order::with('items.product')->findOrFail($id);
        $this->isModalOpen = true;
    }
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedOrder = null; // Очищаем выбранный заказ на всякий случай
    }
}
