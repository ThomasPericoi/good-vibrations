document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const body = document.body;
    const header = document.getElementById('header');
    const main = document.getElementById('main');
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

        if (header) {
            root.style.setProperty('--header-height', `${header.querySelector('.inner-header').offsetHeight}px`);
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

        const sections = Array.from(main.children).filter((element) => element.tagName === 'SECTION');
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
            threshold: 0.1,
        });

        window.requestAnimationFrame(() => {
            window.requestAnimationFrame(() => {
                sections.forEach((section) => observer.observe(section));
            });
        });
    };

    // Preserve custom ordered-list starts inside formatted content.
    const initOrderedLists = () => {
        document.querySelectorAll('.formatted ol[start]').forEach((list) => {
            const start = Number.parseInt(list.getAttribute('start'), 10);

            if (!Number.isNaN(start)) {
                list.style.counterReset = `item ${start - 1}`;
            }
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
            if (event.target.closest('a')) {
                closeMenu();
                menuToggle.focus();
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
                menuToggle.focus();
            }
        });
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
    initOrderedLists();
    initMenu();
    initIntroHeaderState();
});
