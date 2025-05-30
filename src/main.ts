import Alpine from "alpinejs";

// @ts-ignore
import focus from "@alpinejs/focus";
// @ts-ignore
import collapse from "@alpinejs/collapse";

import ElementEntranceObserver from "./utils/element-entrance-observer";

import "./main.css";


import { DvllHeader } from "./components/header";
import gallery from "./components/gallery";

Alpine.plugin(collapse);
Alpine.plugin(focus);
// suggested in the Alpine docs:
// make Alpine on window available for better DX
window.Alpine = Alpine;

Alpine.data("gallery", gallery);

Alpine.start();

customElements.define('dvll-header', DvllHeader);

const entranceObserver = new ElementEntranceObserver();
entranceObserver.startObserving();
