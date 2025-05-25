// ElementEntranceObserver.ts

export default class ElementEntranceObserver {
    private animationQueue: HTMLElement[];
    private animationWorkerTimeout: number | undefined;
    private observer: IntersectionObserver;

    constructor() {
        this.animationQueue = [];
        this.animationWorkerTimeout = undefined;

        const observerOptions: IntersectionObserverInit = {
            root: null,
            rootMargin: '0px 0px 0px 0px',
            threshold: [0, 0.1],
        };

        this.intersectionCallback = this.intersectionCallback.bind(this);
        this.handleElementFromAnimationQueue = this.handleElementFromAnimationQueue.bind(this);
        this.startListWorker = this.startListWorker.bind(this);

        this.observer = new IntersectionObserver(this.intersectionCallback, observerOptions);

        window.addEventListener('elementEntranceObserver.addElements', (event: Event) => {
            const customEvent = event as CustomEvent<{ latestElements: HTMLElement[] }>;
            const { latestElements } = customEvent.detail;

            latestElements.forEach((parent: HTMLElement) => {
                parent.querySelectorAll('[data-entry-listener]').forEach((el) => {
                    this.addElementToObserver(el as HTMLElement);
                });
            });
        });
    }

    public startObserving(): void {
        document.querySelectorAll('[data-entry-listener]').forEach((el) =>
            this.observer.observe(el as Element)
        );
    }

    public addElementToObserver(element: HTMLElement): void {
        this.observer.observe(element);
    }

    private setElementEntryAttribute(element: HTMLElement, isDelayed: boolean = false): void {
        if (isDelayed) {
            element.dataset.isEnteredDelayed = 'true';
        } else {
            element.dataset.isEntered = 'true';
        }
    }

    private intersectionCallback(entries: IntersectionObserverEntry[]): void {
        entries.forEach((entry) => {
            const element = entry.target as HTMLElement;

            // Optional debug for specific element
            if (element.id === 'dvollelem') {
                console.log(element, entry.isIntersecting, entry.intersectionRatio, entry.time);
            }

            if (entry.isIntersecting && entry.intersectionRatio >= 0.1) {
                // Element fully intersected
                window.requestAnimationFrame(() => {
                    this.setElementEntryAttribute(element);
                    if (element.dataset.entryListenerEvent !== undefined) {
                        element.dispatchEvent(new CustomEvent('entry-listener-entered', { bubbles: true }));
                    }
                });

                this.animationQueue.push(element);
                if (!this.animationWorkerTimeout) this.startListWorker();

            } else if (entry.isIntersecting) {
                // Large element edge case
                window.requestAnimationFrame(() => {
                    const { height } = element.getBoundingClientRect();
                    if (height >= window.innerHeight) {
                        this.setElementEntryAttribute(element);
                        if (element.dataset.entryListenerEvent !== undefined) {
                            element.dispatchEvent(new CustomEvent('entry-listener-entered', { bubbles: true }));
                        }
                        this.animationQueue.push(element);
                        if (!this.animationWorkerTimeout) this.startListWorker();
                    }
                });

            } else {
                // Element no longer intersecting
                this.animationQueue = this.animationQueue.filter((queuedElement) => {
                    const isSame = queuedElement === element;
                    if (isSame) {
                        window.requestAnimationFrame(() => {
                            this.setElementEntryAttribute(element, true);
                        });
                    }
                    return !isSame;
                });
            }
        });
    }

    private handleElementFromAnimationQueue(): void {
        let isFirst = true;

        while (isFirst || this.animationQueue.length > 8) {
            isFirst = false;

            const element = this.animationQueue.shift();
            if (element) {
                this.setElementEntryAttribute(element, true);
                if (element.dataset.entryListenerEvent !== undefined) {
                    element.dispatchEvent(
                        new CustomEvent('entry-listener-entered-delayed', { bubbles: true })
                    );
                }
            }
        }
    }

    private startListWorker(): void {
        if (this.animationQueue.length <= 0) {
            clearTimeout(this.animationWorkerTimeout);
            this.animationWorkerTimeout = undefined;
            return;
        }

        if (!this.animationWorkerTimeout) {
            this.animationWorkerTimeout = window.setInterval(this.startListWorker, 100);
        }

        window.requestAnimationFrame(this.handleElementFromAnimationQueue);
    }
}
