class DvllHeader extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
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
