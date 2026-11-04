import { initNav } from './nav';
import { initAnimations } from './animations';
import { initPublicForms } from './public-forms';
import { initHotelGalleries } from './hotel-gallery';
import { initBackToTop } from './back-to-top';
import { initContactModals } from './contact-modal';
import { initBlogRails } from './blog-carousel';
import { initTourDetail } from './tour-detail';
import { initToc } from './toc';
import { initCollectionFilter } from './collection-filter';

document.addEventListener('DOMContentLoaded', () => {
    document.documentElement.classList.remove('no-js');
    initNav();
    initAnimations();
    initPublicForms();
    initHotelGalleries();
    initBackToTop();
    initContactModals();
    initBlogRails();
    initTourDetail();
    initToc();
    initCollectionFilter();
});
