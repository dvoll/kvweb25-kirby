import Alpine from "@alpinejs/csp";

import { DvllHeader } from "./components/header";
import eventDialog, { sharedEventDialog } from "./components/event-dialog";

// suggested in the Alpine docs:
// make Alpine on window available for better DX
window.Alpine = Alpine;

Alpine.data("eventCard", eventDialog); // Individual event cards
Alpine.data("sharedEventDialog", sharedEventDialog); // Single shared dialog


// Function to initialize Alpine
async function initializeAlpine() {
    // Check if gallery is needed before Alpine starts
    const galleryElements = document.querySelectorAll('[x-data*="gallery"]');

    if (galleryElements.length > 0) {
        try {
            const galleryModule = await import('./components/gallery');
            const galleryComponent = galleryModule.default;

            if (galleryComponent) {
                Alpine.data("gallery", galleryComponent);
            }
        } catch (error) {
            console.error('Failed to load gallery component:', error);
            // Provide fallback
            Alpine.data("gallery", () => ({
                error: true,
                init() {
                    console.warn('Gallery component failed to load');
                }
            }));
        }
    }

    // Now start Alpine with all components ready
    Alpine.start();
}


customElements.define('dvll-header', DvllHeader);

window.addEventListener("DOMContentLoaded", () => {
    // Initialize Alpine
    requestAnimationFrame(() => {
        initializeAlpine();
    });

    requestAnimationFrame(() => {
    // Stage welcome animation check and trigger
        if (location.pathname === "/") {
            const hasSeenWelcomeAnimation = sessionStorage.getItem("hasSeenWelcomeAnimation");
            if (!hasSeenWelcomeAnimation) {
                document.body.classList.add("welcome-animation");
                sessionStorage.setItem("hasSeenWelcomeAnimation", "true");
            }
            document.querySelectorAll(".stage-welcome .transition-flip, .stage-welcome .transition-drive").forEach((el) => {
                el.classList.remove("transition--initial");
            });
        }
    });
});

