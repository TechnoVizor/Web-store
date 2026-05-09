<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Users extends Component
{
    use WithPagination;

    public $search = '';

    // Свойства для модалки редактирования
    public $isModalOpen = false;

    public $userId;

    public $name;

    public $email;

    public $phone;

    public $is_admin;

    public $is_super_admin;

    public $canEditUser = true;

    // Свойства для модалки удаления (ВАЖНО!)
    public $isDeleteModalOpen = false;

    public $userIdBeingDeleted = null;

    public $userNameBeingDeleted = '';

    // Метод подтверждения удаления
    public function confirmDelete($id)
    {
        $user = User::findOrFail($id);
        $this->userIdBeingDeleted = $id;
        $this->userNameBeingDeleted = $user->name;
        $this->isDeleteModalOpen = true; // Открываем окно
    }

    public function cancelDelete()
    {
        $this->isDeleteModalOpen = false;
        $this->userIdBeingDeleted = null;
    }

    public function deleteUser($id)
    {
        if ($id === auth()->id()) {
            return;
        }

        $user = User::findOrFail($id);

        // Защита: обычный админ не может удалить супер-админа
        if ($user->is_super_admin && ! auth()->user()->is_super_admin) {
            return;
        }

        $user->delete();
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->is_admin = (bool) $user->is_admin;
        $this->is_super_admin = (bool) $user->is_super_admin;

        // Проверка прав
        $iAmSuper = (bool) auth()->user()->is_super_admin;
        $targetIsSuper = (bool) $user->is_super_admin;
        $this->canEditUser = $iAmSuper || ! $targetIsSuper;

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['userId', 'name', 'email', 'phone', 'is_admin', 'is_super_admin']);
    }

    public function saveUser()
    {
        $targetUser = User::findOrFail($this->userId);

        if ($targetUser->is_super_admin && ! auth()->user()->is_super_admin) {
            return $this->closeModal();
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->userId),
            ],
        ]);

        $targetUser->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_admin' => (bool) $this->is_admin,
        ]);

        if (auth()->user()->is_super_admin && $targetUser->id !== auth()->id()) {
            $targetUser->update(['is_super_admin' => (bool) $this->is_super_admin]);
        }

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.users', [
            'users' => User::where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->latest()
                ->paginate(10),
        ]);
    }
}
