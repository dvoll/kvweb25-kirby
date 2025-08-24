import Alpine from "alpinejs";

import ElementEntranceObserver from "./utils/element-entrance-observer";

import { DvllHeader } from "./components/header";
import gallery from "./components/gallery";
import eventDialog, { sharedEventDialog } from "./components/event-dialog";

// suggested in the Alpine docs:
// make Alpine on window available for better DX
window.Alpine = Alpine;

Alpine.data("gallery", gallery);
Alpine.data("eventCard", eventDialog); // Individual event cards
Alpine.data("sharedEventDialog", sharedEventDialog); // Single shared dialog

Alpine.start();

customElements.define('dvll-header', DvllHeader);

window.addEventListener("DOMContentLoaded", () => {
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

const entranceObserver = new ElementEntranceObserver();
entranceObserver.startObserving();
