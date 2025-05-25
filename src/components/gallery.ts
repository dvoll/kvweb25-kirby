import Swiper from 'swiper';
import 'swiper/css';
import './gallery.css';
import { defineComponent } from './../utils/define-component';

/**
 * @var Alpine
 */
const gallery = defineComponent(() => ({
    swiper: null as Swiper | null,
    isEnd: false as Boolean,
    isBeginning: true as Boolean,
    currentSlide: 0 as Number,
    init() {
        // Register an event handler that references the component instance
        this.swiper = new Swiper(this.$el.querySelector('.swiper') as HTMLElement, {
            slidesPerView: 1,
            spaceBetween: 10,
            centeredSlides: true,
            autoHeight: true,
            breakpoints: {
                640: {
                    spaceBetween: 24,
                },
            },
            on: {
                slideChange: () => {
                    console.log('slide changed');
                    this.isEnd = this.swiper?.isEnd || false;
                    this.isBeginning = this.swiper?.isBeginning || false;
                    this.currentSlide = this.swiper?.activeIndex || 0;
                },
                init: () => {
                    console.log('swiper initialized');
                    this.isEnd = this.swiper?.isEnd || false;
                    this.isBeginning = this.swiper?.isBeginning || false;
                    this.currentSlide = this.swiper?.activeIndex || 0;
                }
            },
        });
    },
    destroy() {
        // Detach the handler, avoiding memory and side-effect leakage
        this.swiper?.destroy();
        this.swiper = null;
    },
    next() {
        this.swiper?.slideNext();
    },
    prev() {
        this.swiper?.slidePrev();
    },
}));

export default gallery;
