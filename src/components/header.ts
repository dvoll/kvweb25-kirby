class DvllHeader extends HTMLElement {
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
        // Add event listeners to nav links with submenus
        const navLinks = this.querySelectorAll('nav.desktop-nav > ul > li > .nav-link');
        navLinks.forEach((link) => {
            const submenu = link.nextElementSibling;
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
            if (openSubmenu && !openSubmenu.contains(event.target)) {
                this.closeAllSubmenus();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                this.closeAllSubmenus();
            }
        });
    }

    closeAllSubmenus() {
        const navLinks = this.querySelectorAll('nav > ul > li > .nav-link');
        navLinks.forEach((link) => {
            link.classList.remove('nav-link--open-submenu');
            const submenu = link.nextElementSibling;
            if (!submenu) return;
            submenu.classList.remove('nav-submenu--open');

        });
    }
}

customElements.define('dvll-header', DvllHeader);
