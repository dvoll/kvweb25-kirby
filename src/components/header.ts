export class DvllHeader extends HTMLElement {
    scrollTimeout: number | null = null;

    constructor() {
        super();
    }

    connectedCallback() {
        // Add scroll listener and add a class if document is scrolled
        const header = this;
        let lastScrollTop = 0;
        const scrollHandler = () => {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > lastScrollTop) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // For Mobile or negative scrolling
        };

        const throttledScrollHandler = () => {
            if (!this.scrollTimeout) {
                this.scrollTimeout = window.setTimeout(() => {
                    scrollHandler();
                    this.scrollTimeout = null;
                }, 100); // Adjust the timeout for performance
            }
        };

        window.addEventListener('scroll', throttledScrollHandler, { passive: true });

        const mainMenuButton = this.querySelector('.main-nav-button');
        const modal = this.querySelector('#mobile-nav-modal') as HTMLDialogElement;
        mainMenuButton?.addEventListener('click', () => {
            modal.showModal();
        });
        const mainMenuCloseButton = this.querySelector('.main-nav-close-button');
        mainMenuCloseButton?.addEventListener('click', () => {
            modal.close();
        });

        const mobileNavToggles = this.querySelectorAll<HTMLButtonElement>('.mobile-nav-toggle');
        mobileNavToggles.forEach((toggle) => {
            const submenuId = toggle.getAttribute('aria-controls');
            if (!submenuId) {
                return;
            }
            const submenu = this.querySelector(`#${submenuId}`) as HTMLElement | null;
            if (!submenu) {
                return;
            }

            toggle.addEventListener('click', () => {
                const isOpen = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', String(!isOpen));
                submenu.classList.toggle('hidden');
                toggle.querySelector('.mobile-nav-toggle-icon')?.classList.toggle('rotate-180');
            });
        });

        modal.addEventListener('close', () => {
            this.closeAllMobileSubmenus();
        });

        // Add event listeners to nav links with submenus
        const navLinks = this.querySelectorAll('nav.desktop-nav > ul > li > .nav-link');
        navLinks.forEach((link) => {
            const submenu = link.nextElementSibling as HTMLElement;
            if (submenu) {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    const isOpen = submenu.classList.contains('nav-submenu--open');
                    this.closeAllSubmenus();
                    if (!isOpen) {
                        submenu.style.display = 'flex'; // Set display to block first
                        submenu.offsetHeight; // Force reflow
                        requestAnimationFrame(() => {
                            submenu.classList.add('nav-submenu--open'); // Trigger CSS transition
                        });
                        link.classList.add('nav-link--open-submenu');
                    }
                });
                submenu.addEventListener('transitionend', () => {
                    if (!submenu.classList.contains('nav-submenu--open')) {
                        submenu.style.display = '';
                    }
                });
            }
        });

        document.addEventListener('click', (event) => {
            const openSubmenu = this.querySelector('nav > ul > li > ul.nav-submenu--open');
            if (openSubmenu && !openSubmenu.contains(event.target as Node)) {
                this.closeAllSubmenus();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.closeAllSubmenus();
                this.closeAllMobileSubmenus();
            }
        });
    }

    closeAllSubmenus() {
        const navLinks = this.querySelectorAll('nav.desktop-nav > ul > li > .nav-link');
        navLinks.forEach((link) => {
            link.classList.remove('nav-link--open-submenu');
            const submenu = link.nextElementSibling;
            if (!submenu) return;
            submenu.classList.remove('nav-submenu--open');
        });
    }

    closeAllMobileSubmenus() {
        const mobileNavToggles = this.querySelectorAll<HTMLButtonElement>('.mobile-nav-toggle');
        mobileNavToggles.forEach((toggle) => {
            toggle.setAttribute('aria-expanded', 'false');
            toggle.querySelector('.mobile-nav-toggle-icon')?.classList.remove('rotate-180');
        });

        const mobileSubmenus = this.querySelectorAll<HTMLElement>('.mobile-nav-submenu');
        mobileSubmenus.forEach((submenu) => submenu.classList.add('hidden'));
    }
}
