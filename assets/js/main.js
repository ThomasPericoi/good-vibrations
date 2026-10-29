document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const body = document.body;
    const header = document.getElementById('header');
    const main = document.getElementById('main');
    const innerHeader = header?.querySelector('.inner-header');
    const footer = document.querySelector('footer');
    const adminBar = body.classList.contains('admin-bar') ? document.getElementById('wpadminbar') : null;
    const nav = document.querySelector('.nav-wrapper');
    const menuToggle = document.getElementById('menu-toggle');
    const reduceMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

    // Toggle inert and aria-hidden together for accessible hidden states.
    const setElementState = (element, enabled) => {
        if (!element) {
            return;
        }

        element.toggleAttribute('inert', !enabled);
        element.setAttribute('aria-hidden', enabled ? 'false' : 'true');
    };

    // Open or close the main navigation and protect the rest of the page.
    const setMenuState = (opened) => {
        body.classList.toggle('js-menuOpened', opened);

        if (menuToggle) {
            menuToggle.setAttribute('aria-expanded', opened ? 'true' : 'false');
            menuToggle.querySelector('.screen-reader-text').textContent = opened ? menuToggle.dataset.labelClose : menuToggle.dataset.labelOpen;
        }

        setElementState(nav, opened);
        header?.querySelector('.header-identity')?.toggleAttribute('inert', opened);
        setElementState(main, !opened);
        setElementState(footer, !opened);
    };

    const closeMenu = () => setMenuState(false);

    // Keep viewport, scrollbar, admin bar, and header values in CSS variables.
    const updateLayoutVariables = () => {
        root.style.setProperty('--viewport-height', `${window.innerHeight}px`);
        root.style.setProperty('--scrollbar-width', `${window.innerWidth - root.clientWidth}px`);
        root.style.setProperty('--admin-bar-visible-height', '0px');

        if (adminBar) {
            const adminBarRect = adminBar.getBoundingClientRect();
            const adminBarVisibleHeight = Math.max(0, Math.min(adminBarRect.bottom, adminBarRect.height));

            root.style.setProperty('--admin-bar-visible-height', `${adminBarVisibleHeight}px`);
        }

        if (innerHeader) {
            root.style.setProperty('--header-height', `${innerHeader.offsetHeight}px`);
        }
    };

    // Batch layout variable updates into the next animation frame.
    let layoutTicking = false;
    const requestLayoutVariablesUpdate = () => {
        if (layoutTicking) {
            return;
        }

        layoutTicking = true;
        window.requestAnimationFrame(() => {
            updateLayoutVariables();
            layoutTicking = false;
        });
    };

    // Reconcile menu and layout state when the viewport changes.
    const onViewportChange = () => {
        updateLayoutVariables();

        setMenuState(body.classList.contains('js-menuOpened'));
    };

    // Bind all layout observers and viewport listeners.
    const initLayoutVariables = () => {
        updateLayoutVariables();
        window.addEventListener('resize', onViewportChange, { passive: true });
        window.addEventListener('scroll', requestLayoutVariablesUpdate, { passive: true });
        window.visualViewport?.addEventListener('resize', updateLayoutVariables, { passive: true });
        window.visualViewport?.addEventListener('scroll', requestLayoutVariablesUpdate, { passive: true });

        if ('ResizeObserver' in window && header) {
            new ResizeObserver(updateLayoutVariables).observe(header);
        }

        if ('ResizeObserver' in window && adminBar) {
            new ResizeObserver(updateLayoutVariables).observe(adminBar);
        }
    };

    // Print the theme signature in the console.
    const initSignature = () => {
        console.info('This theme was made by Thomas Pericoi - https://thomaspericoi.com/');

        if (window.AsciiPrinter && typeof window.AsciiPrinter.printRandom === 'function') {
            window.AsciiPrinter.printRandom();
        }
    };

    // Reveal main sections progressively while keeping fallbacks for old browsers.
    const initRevealOnScroll = () => {
        if (!main) {
            return;
        }

        const sections = Array.from(main.children).filter((element) => element.tagName === 'SECTION' && !element.classList.contains('js-alwaysInView'));
        const revealSection = (section) => section.classList.add('js-inView');

        if (!sections.length || reduceMotionQuery.matches || !('IntersectionObserver' in window)) {
            sections.forEach(revealSection);
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                revealSection(entry.target);
                observer.unobserve(entry.target);
            });
        }, {
            rootMargin: '0px 0px -15% 0px',
            threshold: 0,
        });

        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(() => {
                sections.forEach((section) => observer.observe(section));
            });
        });
    };

    // Wire the responsive menu behaviour.
    const initMenu = () => {
        if (!nav || !menuToggle) {
            return;
        }

        setMenuState(false);

        menuToggle.addEventListener('click', () => {
            setMenuState(!body.classList.contains('js-menuOpened'));
        });

        nav.addEventListener('click', (event) => {
            const link = event.target.closest('a[href]');
            if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                return;
            }

            closeMenu();
            menuToggle.focus({ preventScroll: true });

            const url = new URL(link.href, window.location.href);
            if ((link.target && link.target !== '_self') || link.hasAttribute('download') || url.origin !== window.location.origin || url.pathname !== window.location.pathname || url.search !== window.location.search || !url.hash) {
                return;
            }

            let target;
            try {
                target = document.getElementById(decodeURIComponent(url.hash.slice(1)));
            } catch {
                return;
            }

            if (target) {
                if (!target.hasAttribute('tabindex')) {
                    target.setAttribute('tabindex', '-1');
                    target.addEventListener('blur', () => target.removeAttribute('tabindex'), { once: true });
                }
                // Keep native anchor scrolling and history, without a second scroll.
                target.focus({ preventScroll: true });
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Tab' && body.classList.contains('js-menuOpened')) {
                const controls = [menuToggle, ...nav.querySelectorAll('a[href], button:not([disabled])')];
                const first = controls[0];
                const last = controls[controls.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }

            if (event.key === 'Escape' && body.classList.contains('js-menuOpened')) {
                closeMenu();
                menuToggle.focus({ preventScroll: true });
            }
        });
    };

    // Play the decorative video only while it is visible and motion is allowed.
    const initIntroVideo = () => {
        const video = document.querySelector('.intro-video');
        if (!video) {
            return;
        }

        const intro = video.closest('.front-page-intro');
        if (!intro) {
            return;
        }

        const rect = intro.getBoundingClientRect();
        let visible = rect.bottom > 0 && rect.top < window.innerHeight;
        const updatePlayback = () => {
            if (!visible || document.hidden || reduceMotionQuery.matches) {
                video.pause();
            } else {
                // Autoplay can be blocked by the browser or power-saving mode.
                video.play().catch(() => {});
            }
        };

        updatePlayback();
        reduceMotionQuery.addEventListener('change', updatePlayback);
        document.addEventListener('visibilitychange', updatePlayback);

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(([entry]) => {
                visible = entry.isIntersecting;
                updatePlayback();
            }).observe(intro);
        } else {
            const updateVisibility = () => {
                const rect = intro.getBoundingClientRect();
                visible = rect.bottom > 0 && rect.top < window.innerHeight;
                updatePlayback();
            };
            window.addEventListener('scroll', updateVisibility, { passive: true });
            window.addEventListener('resize', updateVisibility, { passive: true });
        }
    };

    // Keep the header state in sync with the introduction section.
    const initIntroHeaderState = () => {
        const intro = document.querySelector('.front-page-intro');
        if (!intro) {
            return;
        }

        const updateHeader = () => {
            const rect = intro.getBoundingClientRect();
            body.classList.toggle('js-introVisible', rect.top <= (header?.offsetHeight || 0) && rect.bottom > (header?.offsetHeight || 0));
        };

        updateHeader();
        window.addEventListener('scroll', updateHeader, { passive: true });
        window.addEventListener('resize', updateHeader, { passive: true });
    };

    root.classList.add('js-enabled');

    initLayoutVariables();
    initSignature();
    initRevealOnScroll();
    initMenu();
    initIntroHeaderState();
    initIntroVideo();
});
