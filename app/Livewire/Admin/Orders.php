<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Orders extends Component
{
    use WithPagination;

    private const STATUSES = ['new', 'pending', 'processing', 'paid', 'shipped', 'delivered', 'cancelled'];

    public $isModalOpen = false;

    public $selectedOrder = null;

    public $search = '';

    public function updateStatus($orderId, $newStatus)
    {
        validator(
            ['status' => $newStatus],
            ['status' => ['required', Rule::in(self::STATUSES)]]
        )->validate();

        $order = Order::findOrFail($orderId);
        $order->update(['status' => $newStatus]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::query()
            ->withCount('items')
            ->when(filled($this->search), function ($query) {
                $term = '%'.trim($this->search).'%';

                $query->where(function ($query) use ($term) {
                    $query->where('customer_name', 'like', $term)
                        ->orWhere('customer_phone', 'like', $term)
                        ->orWhere('customer_email', 'like', $term)
                        ->orWhere('id', 'like', $term);
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.orders', compact('orders'));
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);

        if (in_array($order->status, ['delivered', 'cancelled'], true)) {
            $order->delete();
        } else {
            session()->flash('error', 'ACCESS_DENIED: Cannot delete active orders.');
        }
    }

    public function openModal($id)
    {
        $this->selectedOrder = Order::with('items.product')->findOrFail($id);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedOrder = null;
    }
}
