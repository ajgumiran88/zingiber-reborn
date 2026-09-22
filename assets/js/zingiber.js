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

        // The head script already decided this view skips the curtain.
        if (document.documentElement.classList.contains('zingiber-intro-skip')) {
            if (preloader.parentNode) {
                preloader.parentNode.removeChild(preloader);
            }
            document.documentElement.classList.remove('zingiber-preload');
            return;
        }

        try {
            window.sessionStorage.setItem('zingiber-intro-seen', '1');
        } catch (e) {}

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

    var backToTop = document.querySelector('[data-zingiber-back-to-top]');

    if (backToTop) {
        var updateBackToTop = function () {
            var threshold = Math.max(window.innerHeight * 0.85, 480);
            backToTop.classList.toggle('is-visible', window.scrollY > threshold);
        };

        backToTop.addEventListener('click', function () {
            if (reducedMotion) {
                window.scrollTo(0, 0);
                return;
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        updateBackToTop();
        window.addEventListener('scroll', updateBackToTop, { passive: true });
        window.addEventListener('resize', updateBackToTop, { passive: true });
    }

    var heroScrollGuide = document.querySelector('[data-zingiber-hero-scroll]');
    var heroSection = document.getElementById('hero');

    if (heroScrollGuide && heroSection) {
        var updateHeroScrollGuide = function () {
            var heroBottom = heroSection.getBoundingClientRect().bottom;
            var hideGuide = window.scrollY > 56 || heroBottom < window.innerHeight * 0.72;
            heroScrollGuide.classList.toggle('is-hidden', hideGuide);
        };

        updateHeroScrollGuide();
        window.addEventListener('scroll', updateHeroScrollGuide, { passive: true });
        window.addEventListener('resize', updateHeroScrollGuide, { passive: true });
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

    /*
     * Gallery: category filtering and a lightbox viewer.
     *
     * Both are enhancements. Without this script the tiles are still links to
     * the full-size photographs and the whole set is on the page, so nothing
     * here is load-bearing for access to the images.
     */
    (function initGallery() {
        var grid = document.querySelector('[data-zingiber-gallery-grid]');

        if (!grid) {
            return;
        }

        var items = Array.prototype.slice.call(grid.querySelectorAll('[data-zingiber-gallery-item]'));

        if (!items.length) {
            return;
        }

        var labels = {
            viewer: grid.getAttribute('data-label-viewer') || 'Gallery viewer',
            close: grid.getAttribute('data-label-close') || 'Close',
            previous: grid.getAttribute('data-label-previous') || 'Previous',
            next: grid.getAttribute('data-label-next') || 'Next',
            counter: grid.getAttribute('data-label-counter') || '%1$s of %2$s'
        };

        // The set the viewer walks through: whatever the active filter shows.
        var visible = items.slice();

        var sourceFor = function (item) {
            var trigger = item.querySelector('[data-zingiber-gallery-open]');

            if (!trigger) {
                return '';
            }

            return trigger.getAttribute('data-zingiber-gallery-src') || trigger.getAttribute('href') || '';
        };

        var captionFor = function (item) {
            var image = item.querySelector('img');

            return image ? (image.getAttribute('alt') || '') : '';
        };

        var filterButtons = Array.prototype.slice.call(document.querySelectorAll('[data-zingiber-gallery-filter]'));
        var countEl = document.querySelector('[data-zingiber-gallery-count]');
        var emptyEl = document.querySelector('[data-zingiber-gallery-empty]');

        var updateCount = function (total) {
            if (!countEl) {
                return;
            }

            var template = total === 1
                ? (countEl.getAttribute('data-count-one') || '%s photograph')
                : (countEl.getAttribute('data-count-other') || '%s photographs');

            countEl.textContent = template.replace('%s', String(total));
        };

        var applyFilter = function (value) {
            visible = [];

            items.forEach(function (item) {
                var match = value === 'all' || item.getAttribute('data-zingiber-gallery-category') === value;

                item.hidden = !match;

                if (match) {
                    visible.push(item);
                }
            });

            if (emptyEl) {
                emptyEl.hidden = visible.length > 0;
            }

            updateCount(visible.length);
        };

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                filterButtons.forEach(function (other) {
                    var isActive = other === button;

                    other.setAttribute('aria-pressed', String(isActive));
                    other.classList.toggle('is-active', isActive);
                });

                applyFilter(button.getAttribute('data-zingiber-gallery-filter'));
            });
        });

        var lightbox = null;
        var lightboxImage = null;
        var lightboxCaption = null;
        var lightboxCounter = null;
        var lightboxLive = null;
        var controls = [];
        var activeIndex = 0;
        var lastFocused = null;
        var swapTimer = 0;

        var icon = function (shape) {
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" '
                + 'stroke-linecap="round" stroke-linejoin="round" focusable="false" aria-hidden="true">'
                + shape + '</svg>';
        };

        var control = function (className, label, shape) {
            var button = document.createElement('button');

            button.type = 'button';
            button.className = 'zingiber-lightbox__control ' + className;
            button.setAttribute('aria-label', label);
            button.innerHTML = icon(shape);

            return button;
        };

        var preloadNeighbours = function () {
            if (visible.length < 2) {
                return;
            }

            [activeIndex + 1, activeIndex - 1].forEach(function (offset) {
                var index = (offset + visible.length) % visible.length;
                var src = sourceFor(visible[index]);

                if (src) {
                    var preload = new window.Image();
                    preload.src = src;
                }
            });
        };

        var render = function (animate) {
            var item = visible[activeIndex];

            if (!item) {
                return;
            }

            var src = sourceFor(item);
            var caption = captionFor(item);
            var counter = labels.counter
                .replace('%1$s', String(activeIndex + 1))
                .replace('%2$s', String(visible.length));

            lightboxCaption.textContent = caption;
            lightboxCounter.textContent = counter;

            /*
             * One region carries the whole phrase, so a screen reader hears
             * "3 of 10, warm dining room" rather than two fragments racing.
             */
            lightboxLive.textContent = counter + '. ' + caption;

            var single = visible.length < 2;

            controls.forEach(function (button) {
                if (button.classList.contains('zingiber-lightbox__close')) {
                    return;
                }

                button.disabled = single;
            });

            var swap = function () {
                lightboxImage.setAttribute('src', src);
                lightbox.classList.remove('is-swapping');
                preloadNeighbours();
            };

            window.clearTimeout(swapTimer);

            if (animate && !reducedMotion) {
                lightbox.classList.add('is-swapping');
                swapTimer = window.setTimeout(swap, 180);
                return;
            }

            swap();
        };

        var step = function (delta) {
            if (visible.length < 2) {
                return;
            }

            activeIndex = (activeIndex + delta + visible.length) % visible.length;
            render(true);
        };

        var focusables = function () {
            return controls.filter(function (button) {
                return !button.disabled;
            });
        };

        var onKeydown = function (event) {
            if (event.key === 'Escape') {
                event.preventDefault();
                close();
                return;
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                step(1);
                return;
            }

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                step(-1);
                return;
            }

            if (event.key !== 'Tab') {
                return;
            }

            // Keep Tab inside the dialog while it owns the screen.
            var order = focusables();

            if (!order.length) {
                return;
            }

            var first = order[0];
            var last = order[order.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
                return;
            }

            if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        };

        var close = function () {
            if (!lightbox || lightbox.hidden) {
                return;
            }

            window.clearTimeout(swapTimer);
            lightbox.classList.remove('is-open');
            document.removeEventListener('keydown', onKeydown, true);

            var finish = function () {
                lightbox.hidden = true;
                lightboxImage.removeAttribute('src');
                document.documentElement.style.overflow = '';
                document.documentElement.style.paddingRight = '';

                if (lastFocused && typeof lastFocused.focus === 'function') {
                    lastFocused.focus();
                }
            };

            if (reducedMotion) {
                finish();
                return;
            }

            window.setTimeout(finish, 240);
        };

        var build = function () {
            lightbox = document.createElement('div');
            lightbox.className = 'zingiber-lightbox';
            lightbox.setAttribute('role', 'dialog');
            lightbox.setAttribute('aria-modal', 'true');
            lightbox.setAttribute('aria-label', labels.viewer);
            lightbox.hidden = true;

            var closeButton = control('zingiber-lightbox__close', labels.close, '<path d="M6 6l12 12M18 6L6 18"></path>');
            var prevButton = control('zingiber-lightbox__prev', labels.previous, '<path d="M15 5l-7 7 7 7"></path>');
            var nextButton = control('zingiber-lightbox__next', labels.next, '<path d="M9 5l7 7-7 7"></path>');

            controls = [closeButton, prevButton, nextButton];

            var figure = document.createElement('figure');
            figure.className = 'zingiber-lightbox__figure';

            var frame = document.createElement('div');
            frame.className = 'zingiber-lightbox__frame';

            lightboxImage = document.createElement('img');
            /*
             * The caption below carries the description, so the image itself is
             * silent rather than reading the same sentence twice.
             */
            lightboxImage.setAttribute('alt', '');
            lightboxImage.setAttribute('decoding', 'async');
            frame.appendChild(lightboxImage);

            lightboxCaption = document.createElement('figcaption');
            lightboxCaption.className = 'zingiber-lightbox__caption';

            figure.appendChild(frame);
            figure.appendChild(lightboxCaption);

            var controlRow = document.createElement('div');
            controlRow.className = 'zingiber-lightbox__controls';

            lightboxCounter = document.createElement('p');
            lightboxCounter.className = 'zingiber-lightbox__counter';

            controlRow.appendChild(prevButton);
            controlRow.appendChild(lightboxCounter);
            controlRow.appendChild(nextButton);

            lightboxLive = document.createElement('p');
            lightboxLive.className = 'zingiber-sr-only';
            lightboxLive.setAttribute('aria-live', 'polite');
            lightboxLive.setAttribute('aria-atomic', 'true');

            lightbox.appendChild(closeButton);
            lightbox.appendChild(figure);
            lightbox.appendChild(controlRow);
            lightbox.appendChild(lightboxLive);

            closeButton.addEventListener('click', close);
            prevButton.addEventListener('click', function () { step(-1); });
            nextButton.addEventListener('click', function () { step(1); });

            // Clicking the surround dismisses; clicking the photograph does not.
            lightbox.addEventListener('click', function (event) {
                if (event.target === lightbox || event.target === figure || event.target === frame) {
                    close();
                }
            });

            var touchStartX = 0;
            var touchStartY = 0;

            lightbox.addEventListener('touchstart', function (event) {
                touchStartX = event.changedTouches[0].clientX;
                touchStartY = event.changedTouches[0].clientY;
            }, { passive: true });

            lightbox.addEventListener('touchend', function (event) {
                var deltaX = event.changedTouches[0].clientX - touchStartX;
                var deltaY = event.changedTouches[0].clientY - touchStartY;

                // Only a deliberate, mostly horizontal swipe moves the set on.
                if (Math.abs(deltaX) < 48 || Math.abs(deltaX) < Math.abs(deltaY)) {
                    return;
                }

                step(deltaX < 0 ? 1 : -1);
            }, { passive: true });

            document.body.appendChild(lightbox);
        };

        var open = function (item, trigger) {
            if (!lightbox) {
                build();
            }

            var index = visible.indexOf(item);

            if (index === -1) {
                return;
            }

            activeIndex = index;
            lastFocused = trigger;

            var scrollbar = window.innerWidth - document.documentElement.clientWidth;

            document.documentElement.style.overflow = 'hidden';

            if (scrollbar > 0) {
                // Hold the page width so nothing shifts behind the viewer.
                document.documentElement.style.paddingRight = scrollbar + 'px';
            }

            lightbox.hidden = false;
            render(false);

            window.requestAnimationFrame(function () {
                lightbox.classList.add('is-open');
            });

            document.addEventListener('keydown', onKeydown, true);
            controls[0].focus();
        };

        items.forEach(function (item) {
            var trigger = item.querySelector('[data-zingiber-gallery-open]');

            if (!trigger) {
                return;
            }

            trigger.addEventListener('click', function (event) {
                // Let modified clicks open the file the way the visitor asked.
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) {
                    return;
                }

                event.preventDefault();
                open(item, trigger);
            });
        });
    }());
}());
