import { initNav } from './nav';
import { initAnimations } from './animations';
import { initCaptcha } from './captcha';
import { initHotelGalleries } from './hotel-gallery';
import { initBackToTop } from './back-to-top';
import { initContactModals } from './contact-modal';
import { initBlogRails } from './blog-carousel';
import { initTourDetail } from './tour-detail';
import { initToc } from './toc';

document.addEventListener('DOMContentLoaded', () => {
    document.documentElement.classList.remove('no-js');
    initNav();
    initAnimations();
    initCaptcha();
    initHotelGalleries();
    initBackToTop();
    initContactModals();
    initBlogRails();
    initTourDetail();
    initToc();
});
