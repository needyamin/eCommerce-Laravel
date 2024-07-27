'use strict';

// Modal variables
const modal = document.querySelector('[data-modal]');
const modalCloseBtn = document.querySelector('[data-modal-close]');
const modalCloseOverlay = document.querySelector('[data-modal-overlay]');

// Modal function
const modalCloseFunc = function () {
    if (modal) {
        modal.classList.add('closed');
    }
}

// Modal event listeners
if (modalCloseOverlay) {
    modalCloseOverlay.addEventListener('click', modalCloseFunc);
} else {
    console.error('modalCloseOverlay element not found');
}

if (modalCloseBtn) {
    modalCloseBtn.addEventListener('click', modalCloseFunc);
} else {
    console.error('modalCloseBtn element not found');
}

// Notification toast variables
const notificationToast = document.querySelector('[data-toast]');
const toastCloseBtn = document.querySelector('[data-toast-close]');

// Notification toast event listener
if (toastCloseBtn && notificationToast) {
    toastCloseBtn.addEventListener('click', function () {
        notificationToast.classList.add('closed');
    });
} else {
    if (!toastCloseBtn) console.error('toastCloseBtn element not found');
    if (!notificationToast) console.error('notificationToast element not found');
}

// Mobile menu variables
const mobileMenuOpenBtns = document.querySelectorAll('[data-mobile-menu-open-btn]');
const mobileMenus = document.querySelectorAll('[data-mobile-menu]');
const mobileMenuCloseBtns = document.querySelectorAll('[data-mobile-menu-close-btn]');
const overlay = document.querySelector('[data-overlay]');

const mobileMenuCloseFunc = function () {
    for (let i = 0; i < mobileMenus.length; i++) {
        mobileMenus[i].classList.remove('active');
    }
    if (overlay) overlay.classList.remove('active');
}

for (let i = 0; i < mobileMenuOpenBtns.length; i++) {
    const openBtn = mobileMenuOpenBtns[i];
    const closeBtn = mobileMenuCloseBtns[i];
    const menu = mobileMenus[i];

    if (openBtn) {
        openBtn.addEventListener('click', function () {
            if (menu) {
                menu.classList.add('active');
            }
            if (overlay) {
                overlay.classList.add('active');
            }
        });
    } else {
        console.error('mobileMenuOpenBtn element not found');
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', mobileMenuCloseFunc);
    } else {
        console.error('mobileMenuCloseBtn element not found');
    }

    if (overlay) {
        overlay.addEventListener('click', mobileMenuCloseFunc);
    } else {
        console.error('overlay element not found');
    }
}

// Accordion variables
const accordionBtns = document.querySelectorAll('[data-accordion-btn]');
const accordions = document.querySelectorAll('[data-accordion]');

for (let i = 0; i < accordionBtns.length; i++) {
    const accordionBtn = accordionBtns[i];
    const accordionContent = accordions[i];

    if (accordionBtn && accordionContent) {
        accordionBtn.addEventListener('click', function () {
            const isActive = accordionContent.classList.contains('active');

            for (let j = 0; j < accordions.length; j++) {
                if (isActive) break;

                if (accordions[j].classList.contains('active')) {
                    accordions[j].classList.remove('active');
                    accordionBtns[j].classList.remove('active');
                }
            }

            accordionContent.classList.toggle('active');
            accordionBtn.classList.toggle('active');
        });
    } else {
        console.error('Accordion button or content not found');
    }
}
