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
            'text' => 'SYSTEM_READY: Добро пожаловать в DIGI_STORE. Чем я могу помочь?'
        ];
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }
    public $isTyping = false;
    public function sendMessage($text = null)
    {
        $messageText = $text ?? $this->userInput;
        if (empty($messageText)) return;

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
    $response = "ERROR: Команда не распознана. Попробуйте выбрать запрос из меню.";

    if (str_contains($input, 'доставк')) {
        $response = "INFO: Мы доставляем цифровые товары мгновенно. Физические — от 3 до 5 дней.";
    } elseif (str_contains($input, 'оплат')) {
        $response = "INFO: Доступна оплата картами, Apple Pay и криптовалютой.";
    } elseif (str_contains($input, 'контакт')) {
        $response = "SUPPORT: Наш Telegram: @digi_store_tech. Мы онлайн 24/7.";
    } elseif (str_contains($input, 'гарант')) { // ДОБАВЛЯЕМ ЭТОТ БЛОК
        $response = "WARRANTY: 12 месяцев на все оборудование и 14 дней на возврат цифровых ключей (если они не были активированы).";
    }

    $this->messages[] = ['role' => 'system', 'text' => $response];
}

    public function render()
    {
        return view('livewire.chat-bot');
    }
}