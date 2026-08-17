(function () {
    'use strict';

    document.documentElement.classList.add('zingiber-js');

    var header = document.querySelector('[data-zingiber-header]');
    var toggle = document.querySelector('[data-zingiber-menu-toggle]');
    var menu = document.querySelector('[data-zingiber-menu]');

    if (header) {
        var updateStickyHeader = function () {
            header.classList.toggle('is-sticky', window.scrollY > 16);
        };

        updateStickyHeader();
        window.addEventListener('scroll', updateStickyHeader, { passive: true });
    }

    if (toggle && menu) {
        var closeMenu = function (restoreFocus) {
            menu.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');

            if (restoreFocus) {
                toggle.focus();
            }
        };

        toggle.addEventListener('click', function () {
            var isOpen = toggle.getAttribute('aria-expanded') === 'true';
            menu.classList.toggle('is-open', !isOpen);
            toggle.setAttribute('aria-expanded', String(!isOpen));
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && menu.classList.contains('is-open')) {
                closeMenu(true);
            }
        });

        document.addEventListener('click', function (event) {
            if (!menu.classList.contains('is-open')) {
                return;
            }

            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                closeMenu(false);
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 900 && menu.classList.contains('is-open')) {
                closeMenu(false);
            }
        });
    }

    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var revealItems = document.querySelectorAll('[data-zingiber-reveal]');

    if (!reducedMotion && revealItems.length && 'IntersectionObserver' in window) {
        document.documentElement.classList.add('zingiber-enhanced-motion');
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: '0px 0px -8% 0px',
            threshold: 0.12
        });

        revealItems.forEach(function (item) {
            observer.observe(item);
        });
    }
}());
