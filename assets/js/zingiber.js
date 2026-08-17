(function () {
    'use strict';

    document.documentElement.classList.add('zingiber-js');

    var toggle = document.querySelector('[data-zingiber-menu-toggle]');
    var menu = document.querySelector('[data-zingiber-menu]');

    if (!toggle || !menu) {
        return;
    }

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
}());
