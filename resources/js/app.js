// Dark mode harus dijalankan sebelum Alpine
import { initializeDarkMode } from './dark-mode.js';

import Alpine from 'alpinejs';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import Swal from 'sweetalert2';
import '@fortawesome/fontawesome-free/css/all.css';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

window.Alpine = Alpine;
window.Swal = Swal;
Alpine.start();

// Initialize dark mode after Alpine is started
document.addEventListener('DOMContentLoaded', () => {
    console.log('[App] DOM Content Loaded - initializing dark mode');
    initializeDarkMode();
});

// Also try immediate initialization in case DOMContentLoaded already fired
if (document.readyState !== 'loading') {
    console.log('[App] Document already loaded - initializing dark mode immediately');
    initializeDarkMode();
}

// Initialize Swiper carousels when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    initializeCarousels();
});

function initializeCarousels() {
    // Helper function to check if carousel has enough slides for loop
    function shouldEnableLoop(selector, minSlidesRequired = 3) {
        const container = document.querySelector(selector);
        if (!container) return false;
        const slideCount = container.querySelectorAll('.swiper-slide').length;
        return slideCount >= minSlidesRequired;
    }

    // Breaking Strip Carousel
    if (document.querySelector('.carousel-breaking-strip')) {
        const breakingSwiper = new Swiper('.carousel-breaking-strip', {
            modules: [Navigation, Pagination, Autoplay],
            loop: shouldEnableLoop('.carousel-breaking-strip', 4),
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.carousel-breaking-strip .swiper-button-next',
                prevEl: '.carousel-breaking-strip .swiper-button-prev',
            },
            slidesPerView: 1,
            spaceBetween: 24,
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    }

    // Hero Carousel
    if (document.querySelector('.carousel-hero-main')) {
        const heroSwiper = new Swiper('.carousel-hero-main', {
            modules: [Navigation, Autoplay],
            loop: shouldEnableLoop('.carousel-hero-main', 2),
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.carousel-hero-main .swiper-button-next',
                prevEl: '.carousel-hero-main .swiper-button-prev',
            },
            slidesPerView: 1,
            spaceBetween: 0,
        });
    }

    // Category Strip Carousel
    if (document.querySelector('.carousel-category-strip')) {
        const categorySwiper = new Swiper('.carousel-category-strip', {
            modules: [Autoplay],
            loop: shouldEnableLoop('.carousel-category-strip', 5),
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            slidesPerView: 2,
            spaceBetween: 16,
            breakpoints: {
                768: {
                    slidesPerView: 3,
                    spaceBetween: 16,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 16,
                },
            },
        });
    }

    // Sports Carousel
    if (document.querySelector('.carousel-sports')) {
        const sportsSwiper = new Swiper('.carousel-sports', {
            modules: [Autoplay],
            loop: shouldEnableLoop('.carousel-sports', 5),
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            slidesPerView: 2,
            spaceBetween: 16,
            breakpoints: {
                768: {
                    slidesPerView: 3,
                    spaceBetween: 16,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 16,
                },
            },
        });
    }
}
