(function () {
    'use strict';

    document.documentElement.classList.add('zingiber-js');

    var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Ballena-style wheel inertia. Off when user asks for reduced motion. */
    var lenis = null;
    if (!reducedMotion && typeof window.Lenis === 'function') {
        lenis = new window.Lenis({
            autoRaf: true,
            lerp: 0.09,
            smoothWheel: true,
            syncTouch: true,
            syncTouchLerp: 0.075,
            touchInertiaMultiplier: 35
        });
        document.documentElement.classList.add('zingiber-lenis');
    }

    var stopLenis = function () {
        if (lenis) {
            lenis.stop();
        }
    };
    var startLenis = function () {
        if (lenis) {
            lenis.start();
        }
    };

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

    var heroVideo = document.querySelector('[data-zingiber-hero-video]');
    if (heroVideo && !reducedMotion && heroVideo.querySelector('source[src]')) {
        var showPoster = function () {
            heroVideo.hidden = true;
            heroVideo.pause();
        };
        heroVideo.addEventListener('error', showPoster);
        heroVideo.querySelector('source').addEventListener('error', showPoster);
        heroVideo.addEventListener('playing', function () { heroVideo.hidden = false; });
        heroVideo.play().catch(showPoster);
    }

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
            if (lenis && !reducedMotion) {
                lenis.scrollTo(0);
                return;
            }
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
        var closeToggle = menu.querySelector('[data-zingiber-menu-close]');
        var enterTimer = 0;
        var closeMenu = function () {
            menu.close();
        };

        menu.addEventListener('close', function () {
            window.clearTimeout(enterTimer);
            menu.classList.remove('is-entering');
            toggle.setAttribute('aria-expanded', 'false');
            document.documentElement.classList.remove('zingiber-menu-open');
            toggle.focus({ preventScroll: true });
            startLenis();
        });

        toggle.addEventListener('click', function () {
            window.clearTimeout(enterTimer);
            stopLenis();
            menu.classList.remove('is-entering');
            menu.showModal();
            toggle.setAttribute('aria-expanded', 'true');
            document.documentElement.classList.add('zingiber-menu-open');

            // Restart staggered entrance on every open (keyframes do not re-fire alone).
            if (!reducedMotion) {
                void menu.offsetWidth;
                menu.classList.add('is-entering');
                enterTimer = window.setTimeout(function () {
                    menu.classList.remove('is-entering');
                }, 900);
            }
        });

        closeToggle.addEventListener('click', closeMenu);
        menu.addEventListener('click', function (event) {
            if (event.target.closest('a')) {
                closeMenu();
            }
        });

        // Native modal dialogs make the page inert and handle Escape.
        menu.addEventListener('keydown', function (event) {
            if (event.key !== 'Tab') {
                return;
            }
            var links = menu.querySelectorAll('a, button');
            var first = links[0];
            var last = links[links.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        });
    }

    var coast = document.querySelector('[data-zingiber-coast]');
    if (coast) {
        var track = coast.querySelector('[data-zingiber-coast-track]');
        var slides = Array.prototype.slice.call(track.children);
        var indexLabel = coast.querySelector('[data-zingiber-coast-index]');
        var progress = coast.querySelector('[data-zingiber-coast-progress]');
        var previous = coast.querySelector('[data-zingiber-coast-step="-1"]');
        var next = coast.querySelector('[data-zingiber-coast-step="1"]');
        var activeSlide = 0;
        var updateCoast = function () {
            var center = track.getBoundingClientRect().left + track.clientWidth / 2;
            var closest = Infinity;
            slides.forEach(function (slide, index) {
                var bounds = slide.getBoundingClientRect();
                var distance = Math.abs(bounds.left + bounds.width / 2 - center);
                if (distance < closest) {
                    closest = distance;
                    activeSlide = index;
                }
            });
            indexLabel.textContent = String(activeSlide + 1).padStart(2, '0');
            progress.value = activeSlide + 1;
            previous.disabled = activeSlide === 0;
            next.disabled = activeSlide === slides.length - 1;
        };
        var goToCoast = function (index) {
            var slide = slides[Math.max(0, Math.min(slides.length - 1, index))];
            track.scrollTo({
                left: slide.offsetLeft - (track.clientWidth - slide.offsetWidth) / 2,
                behavior: reducedMotion ? 'instant' : 'smooth'
            });
        };
        previous.addEventListener('click', function () { goToCoast(activeSlide - 1); });
        next.addEventListener('click', function () { goToCoast(activeSlide + 1); });
        track.addEventListener('scroll', updateCoast, { passive: true });
        window.addEventListener('resize', updateCoast, { passive: true });
        track.addEventListener('keydown', function (event) {
            var destinations = { ArrowLeft: activeSlide - 1, ArrowRight: activeSlide + 1, Home: 0, End: slides.length - 1 };
            if (Object.prototype.hasOwnProperty.call(destinations, event.key)) {
                event.preventDefault();
                goToCoast(destinations[event.key]);
            }
        });
        updateCoast();
    }

    /* Keep nested horizontal rails off Lenis touch so swipe stays local. */
    document.querySelectorAll('.zingiber-principle-cards, .zingiber-coastal__track').forEach(function (rail) {
        rail.setAttribute('data-lenis-prevent-touch', '');
    });

    var principles = document.querySelector('.zingiber-principle-cards');
    if (principles) {
        var previousPrinciple = document.querySelector('[data-zingiber-principles-step="-1"]');
        var nextPrinciple = document.querySelector('[data-zingiber-principles-step="1"]');
        var principlesProgress = document.querySelector('[data-zingiber-principles-progress]');
        var updatePrinciples = function () {
            var maximum = principles.scrollWidth - principles.clientWidth;
            previousPrinciple.disabled = principles.scrollLeft <= 2;
            nextPrinciple.disabled = principles.scrollLeft >= maximum - 2;
            principlesProgress.value = maximum > 0 ? principles.scrollLeft / maximum * 100 : 100;
        };
        var movePrinciples = function (direction) {
            var cards = principles.children;
            var step = cards.length > 1 ? cards[1].offsetLeft - cards[0].offsetLeft : principles.clientWidth;
            principles.scrollTo({ left: principles.scrollLeft + direction * step, behavior: reducedMotion ? 'instant' : 'smooth' });
        };
        previousPrinciple.addEventListener('click', function () { movePrinciples(-1); });
        nextPrinciple.addEventListener('click', function () { movePrinciples(1); });
        principles.addEventListener('scroll', updatePrinciples, { passive: true });
        window.addEventListener('resize', updatePrinciples, { passive: true });
        principles.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                event.preventDefault();
                movePrinciples(event.key === 'ArrowLeft' ? -1 : 1);
            } else if (event.key === 'Home' || event.key === 'End') {
                event.preventDefault();
                principles.scrollTo({ left: event.key === 'Home' ? 0 : principles.scrollWidth, behavior: reducedMotion ? 'instant' : 'smooth' });
            }
        });
        updatePrinciples();
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
                startLenis();

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

            stopLenis();
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
