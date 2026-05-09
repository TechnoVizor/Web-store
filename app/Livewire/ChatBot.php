<?php

namespace App\Livewire;

use Livewire\Component;

class ChatBot extends Component
{
    public $isOpen = false;

    public $messages = [];

    public $userInput = '';

    public function mount()
    {
        // Приветственное сообщение при первом открытии
        $this->messages[] = [
            'role' => 'system',
            'text' => __('ui.chat.welcome'),
        ];
    }

    public function toggleChat()
    {
        $this->isOpen = ! $this->isOpen;
    }

    public $isTyping = false;

    public function sendMessage($text = null)
    {
        $messageText = $text ?? $this->userInput;
        if (empty($messageText)) {
            return;
        }

        // Добавляем сообщение пользователя
        $this->messages[] = ['role' => 'user', 'text' => $messageText];
        $this->userInput = '';

        // Простая логика ответов (FAQ)
        $this->generateResponse($messageText);
        $this->dispatch('scroll-chat');
    }

    protected function generateResponse($input)
    {
        $input = mb_strtolower($input);
        $response = __('ui.chat.fallback');

        if (str_contains($input, 'доставк') || str_contains($input, 'delivery') || str_contains($input, 'pieg')) {
            $response = __('ui.chat.delivery');
        } elseif (str_contains($input, 'оплат') || str_contains($input, 'payment') || str_contains($input, 'apmak')) {
            $response = __('ui.chat.payment');
        } elseif (str_contains($input, 'возврат') || str_contains($input, 'return') || str_contains($input, 'atgrie')) {
            $response = __('ui.chat.returns');
        }

        $this->messages[] = ['role' => 'system', 'text' => $response];
    }

    public function render()
    {
        return view('livewire.chat-bot');
    }
}
