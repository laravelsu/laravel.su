import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.startSnowfall();
    }

    disconnect() {
        this.clearSnowfall();
    }

    /**
     * Возвращает количество снежинок в зависимости от ширины экрана.
     */
    getSnowflakeCount() {
        const screenWidth = window.innerWidth;

        if (screenWidth < 576) {
            return 20; // Мобильные устройства
        }

        if (screenWidth < 768) {
            return 40; // Планшеты
        }

        if (screenWidth < 1200) {
            return 60; // Небольшие экраны
        }

        return 100; // Большие экраны
    }

    /**
     * Запускает снегопад, создавая снежинки на странице.
     */
    startSnowfall() {
        const snowflakeCount = this.getSnowflakeCount();
        const svgUrls = [
            '/img/ui/santa/snowflake-2.svg',
            '/img/ui/santa/snowflake-3.svg',
            '/img/ui/santa/snowflake-4.svg',
            '/img/ui/santa/snowflake-5.svg',
            '/img/ui/santa/snowflake-6.svg',
            '/img/ui/santa/snowflake-7.svg',
        ];

        for (let i = 0; i < snowflakeCount; i++) {
            const snowflake = this.createSnowflake(svgUrls);
            this.element.appendChild(snowflake);
        }
    }

    /**
     * Создаёт элемент снежинки с рандомными стилями.
     */
    createSnowflake(svgUrls) {
        const snowflake = document.createElement('img');
        snowflake.classList.add('snowflake');

        snowflake.src = svgUrls[Math.floor(Math.random() * svgUrls.length)];

        snowflake.style.setProperty('--snowflake-size', `${this.getRandomInRange(0.5, 1, 2)}rem`);
        snowflake.style.setProperty('--snowflake-left', `${this.getRandomInRange(0, 100, 5)}vw`);
        snowflake.style.setProperty('--fall-duration', `${this.getRandomInRange(5, 20, 2)}s`);
        snowflake.style.setProperty('--fall-delay', `${this.getRandomInRange(0, 5, 1)}s`);

        return snowflake;
    }

    /**
     * Генерирует случайное значение в заданном диапазоне с отклонением.
     */
    getRandomInRange(min, max, deviation = 0) {
        const randomValue = Math.random() * (max - min) + min;
        const offset = (Math.random() - 0.5) * deviation;

        return randomValue + offset;
    }
}
