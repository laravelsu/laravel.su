import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'game',
        'ship',
        'comet',
        'message',
        'timer',
        'audioBackground',
        'startPlaceholder',
        'endPlaceholder',
    ];

    connect() {
        this.svg = this.gameTarget;
        this.shipWidth = 100;
        this.shipHeight = 100;
        this.shipSpeed = 5;
        this.baseCometSpeed = 3;
        this.cometWidth = 60;
        this.cometHeight = 60;
        this.cometInterval = 1000;
        document.addEventListener('keydown', this.keyDownHandler.bind(this));
        document.addEventListener('keyup', this.keyUpHandler.bind(this));
        this.audioBackgroundTarget.loop = true;
        this.fanny = false;
    }

    disconnect() {
        document.removeEventListener('keydown', this.keyDownHandler.bind(this));
        document.removeEventListener('keyup', this.keyUpHandler.bind(this));
    }

    fresh() {
        while (this.svg.firstChild) {
            this.svg.removeChild(this.svg.firstChild);
        }

        this.timer = 0;
        this.cometSpeed = this.baseCometSpeed;
        this.shipX = (this.svg.width.baseVal.value - this.shipWidth) / 2;
        this.shipY = this.svg.height.baseVal.value - this.shipHeight - 10;
        this.leftPressed = false;
        this.rightPressed = false;
        this.comets = [];
        this.shipElement = this.createShipElement();
        this.intervalComet = null;
        this.intervalGame = null;
    }

    keyDownHandler(event) {
        if (event.key === 'ArrowLeft') {
            this.leftPressed = true;
        } else if (event.key === 'ArrowRight') {
            this.rightPressed = true;
        }
    }

    keyUpHandler(event) {
        if (event.key === 'ArrowLeft') {
            this.leftPressed = false;
        } else if (event.key === 'ArrowRight') {
            this.rightPressed = false;
        }
    }

    createShipElement() {
        const shipElement = this.shipTarget.cloneNode(true);
        shipElement.setAttribute('x', this.shipX);
        shipElement.setAttribute('y', this.shipY);
        shipElement.setAttribute('width', this.shipWidth);
        shipElement.setAttribute('height', this.shipHeight);
        shipElement.setAttribute('fill', '#FFFFFF');
        this.svg.appendChild(shipElement);
        return shipElement;
    }

    moveShip() {
        if (this.leftPressed && this.shipX > 0) {
            this.shipX -= this.shipSpeed;
        } else if (this.rightPressed && this.shipX < this.svg.width.baseVal.value - this.shipWidth) {
            this.shipX += this.shipSpeed;
        }
        this.shipElement.setAttribute('x', this.shipX);
    }

    createCometElement(x, y) {
        const cometElement = this.cometTarget.cloneNode(true);
        cometElement.setAttribute('x', x);
        cometElement.setAttribute('y', y);
        cometElement.setAttribute('width', this.cometWidth);
        cometElement.setAttribute('height', this.cometHeight);
        cometElement.setAttribute('fill', '#FF0000');
        this.svg.appendChild(cometElement);
        return cometElement;
    }

    moveComets() {
        this.comets.forEach((comet) => {
            comet.y += this.cometSpeed;
            comet.x = comet.element.getAttribute('x');
            comet.x -= this.cometSpeed;
            const dx = this.shipX - comet.x;
            const dy = this.shipY - comet.y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            const vx = dx / distance;
            const vy = dy / distance;
            if (distance > 100) {
                comet.x += vx;
                comet.y += vy;
            }
            comet.element.setAttribute('x', comet.x);
            comet.element.setAttribute('y', comet.y);
            if (comet.y > this.svg.height.baseVal.value) {
                this.svg.removeChild(comet.element);
                this.comets.splice(this.comets.indexOf(comet), 1);
            }
        });
    }

    createComet() {
        let x = Math.random() * this.svg.width.baseVal.value + this.svg.width.baseVal.value * 0.5;
        if (this.shipX > this.svg.width.baseVal.value / 3) {
            x += this.shipX * 0.5;
        }
        const y = 0;
        const cometElement = this.createCometElement(x, y);
        this.comets.push({ element: cometElement, y: y });
    }

    collisionDetection(fuzziness) {
        const shipRect = this.shipElement.getBoundingClientRect();
        this.comets.forEach((comet) => {
            const cometRect = comet.element.getBoundingClientRect();
            const fuzzinessFactor =
                fuzziness * Math.min(shipRect.width, shipRect.height, cometRect.width, cometRect.height);
            if (
                shipRect.left + fuzzinessFactor < cometRect.right &&
                shipRect.right - fuzzinessFactor > cometRect.left &&
                shipRect.top + fuzzinessFactor < cometRect.bottom &&
                shipRect.bottom - fuzzinessFactor > cometRect.top
            ) {
                clearInterval(this.intervalComet);
                clearInterval(this.intervalGame);

                this.messageTarget.innerText = 'Игра окончена';
                this.startPlaceholderTarget.style.visibility = 'visible';
            }
        });
    }

    stats() {
        this.timer = this.timer + 0.5;
        let value = (this.timer / 100).toFixed(2);

        this.timerTarget.innerText = value;
        this.cometSpeed = this.baseCometSpeed + value * 0.1;

        if (value > 20 && !this.fanny) {
            clearInterval(this.intervalComet);
            clearInterval(this.intervalGame);

            this.endPlaceholderTarget.classList.remove('visually-hidden');
        }
    }

    draw() {
        this.moveShip();
        this.moveComets();
        this.collisionDetection(0.4); // Погрешность 40%
        this.stats();
    }

    start() {
        if (this.audioBackgroundTarget.currentTime === 0) {
            this.audioBackgroundTarget.play();
        }

        this.fresh();
        this.intervalComet = setInterval(this.createComet.bind(this), this.cometInterval);
        this.intervalGame = setInterval(this.draw.bind(this), 10);

        this.messageTarget.innerText = 'Поехали!';

        this.startPlaceholderTarget.style.visibility = 'hidden';
        this.endPlaceholderTarget.classList.add('visually-hidden');
    }

    continue() {
        this.fanny = true;

        this.endPlaceholderTarget.classList.add('visually-hidden');
        this.intervalComet = setInterval(this.createComet.bind(this), this.cometInterval);
        this.intervalGame = setInterval(this.draw.bind(this), 10);
    }
}
