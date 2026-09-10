(function () {
    'use strict';

    document.documentElement.classList.add('zingiber-js');

    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    (function initPreloader() {
        var preloader = document.querySelector('[data-zingiber-preloader]');
        if (!preloader) {
            document.documentElement.classList.remove('zingiber-preload');
            return;
        }

        var bar = preloader.querySelector('[data-zingiber-preloader-bar]');
        var progressEl = preloader.querySelector('[data-zingiber-preloader-progress]');
        var startedAt = Date.now();
        var minDuration = reducedMotion ? 0 : 1100;
        var maxDuration = reducedMotion ? 200 : 3200;
        var progress = 0;
        var loaded = document.readyState === 'complete';
        var finished = false;
        var finishScheduled = false;
        var rafId = 0;

        var setProgress = function (value) {
            progress = Math.max(progress, Math.min(100, value));
            if (bar) {
                bar.style.transform = 'scaleX(' + (progress / 100) + ')';
            }
            if (progressEl) {
                progressEl.setAttribute('aria-valuenow', String(Math.round(progress)));
            }
        };

        var tick = function () {
            if (finished || loaded) {
                return;
            }

            var elapsed = Date.now() - startedAt;
            var target = Math.min(85, 18 + elapsed / 28);
            setProgress(progress + (target - progress) * 0.08);
            rafId = window.requestAnimationFrame(tick);
        };

        var dismiss = function () {
            if (finished) {
                return;
            }
            finished = true;

            if (rafId) {
                window.cancelAnimationFrame(rafId);
            }

            setProgress(100);
            preloader.classList.add('is-finishing');
            preloader.setAttribute('aria-busy', 'false');

            var exitDelay = reducedMotion ? 0 : 420;
            window.setTimeout(function () {
                preloader.classList.add('is-done');
                document.documentElement.classList.remove('zingiber-preload');

                window.setTimeout(function () {
                    if (preloader.parentNode) {
                        preloader.parentNode.removeChild(preloader);
                    }
                }, reducedMotion ? 0 : 700);
            }, exitDelay);
        };

        var tryFinish = function () {
            if (finishScheduled || finished) {
                return;
            }
            finishScheduled = true;

            var elapsed = Date.now() - startedAt;
            var wait = Math.max(0, minDuration - elapsed);
            window.setTimeout(dismiss, wait);
        };

        if (!reducedMotion) {
            rafId = window.requestAnimationFrame(tick);
        } else {
            setProgress(100);
        }

        // Safety cap so the curtain never sticks
        window.setTimeout(function () {
            loaded = true;
            tryFinish();
        }, maxDuration);

        if (loaded) {
            tryFinish();
        } else {
            window.addEventListener('load', function () {
                loaded = true;
                setProgress(92);
                tryFinish();
            });
        }
    }());

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
