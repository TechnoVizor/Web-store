import './bootstrap';



    document.addEventListener('alpine:init', () => {
        Alpine.data('typewriter', (text, speed = 100) => ({
            displayText: '',
            fullText: text,
            currentSpeed: speed,
            init() {
                let currentIndex = 0;
                this.displayText = ''; // Начинаем с пустой строки
                
                const type = () => {
                    if (currentIndex < this.fullText.length) {
                        this.displayText += this.fullText[currentIndex];
                        currentIndex++;
                        // Устанавливаем таймер для следующей буквы
                        setTimeout(type, this.currentSpeed);
                    }
                };
                
                type(); // Запускаем процесс
            }
        }))
    })
