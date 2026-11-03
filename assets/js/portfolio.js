document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.portfolio-track');
    if (!track) {
        return;
    }

    const desktop = window.matchMedia('(min-width: 992px)');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const slides = Array.from(track.children);
    const getSlideLeft = (slide) => slide.getBoundingClientRect().left - track.getBoundingClientRect().left + track.scrollLeft;
    const getCurrentIndex = () => {
        let closest = 0;
        let distance = Infinity;
        slides.forEach((slide, index) => {
            const difference = Math.abs(getSlideLeft(slide) - track.scrollLeft);
            if (difference < distance) {
                closest = index;
                distance = difference;
            }
        });
        return closest;
    };
    const goToSlide = (slide, focus = true) => {
        const left = getSlideLeft(slide);
        track.scrollTo({ left, behavior: reducedMotion.matches ? 'instant' : 'smooth' });
        if (focus) {
            slide.focus({ preventScroll: true });
        }
    };

    // Mouse dragging complements native touch scrolling.
    let drag = null;
    let suppressClick = false;
    track.addEventListener('pointerdown', (event) => {
        if (!desktop.matches || event.pointerType !== 'mouse' || event.button !== 0 || event.target.closest('a, button, input, textarea, select, [contenteditable]')) {
            return;
        }
        drag = { id: event.pointerId, x: event.clientX, left: track.scrollLeft, moved: false };
    });

    track.addEventListener('pointermove', (event) => {
        if (!drag || event.pointerId !== drag.id) {
            return;
        }
        const distance = event.clientX - drag.x;
        if (!drag.moved && Math.abs(distance) < 6) {
            return;
        }
        if (!drag.moved) {
            drag.moved = true;
            track.classList.add('js-dragging');
            track.setPointerCapture(drag.id);
        }
        event.preventDefault();
        track.scrollLeft = drag.left - distance;
    });

    const endDrag = (snap = true) => {
        if (!drag) {
            return;
        }
        const { id, moved } = drag;
        const index = getCurrentIndex();
        drag = null;
        track.classList.remove('js-dragging');
        if (track.hasPointerCapture(id)) {
            track.releasePointerCapture(id);
        }
        if (moved) {
            suppressClick = true;
            window.setTimeout(() => { suppressClick = false; }, 0);
            if (snap && desktop.matches) {
                goToSlide(slides[index], false);
            }
        }
    };

    window.addEventListener('pointerup', () => endDrag());
    window.addEventListener('pointercancel', () => endDrag());
    window.addEventListener('blur', () => endDrag());
    track.addEventListener('lostpointercapture', () => endDrag());
    track.addEventListener('dragstart', (event) => {
        if (drag) {
            event.preventDefault();
        }
    });
    track.addEventListener('click', (event) => {
        if (suppressClick) {
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    }, true);

    track.addEventListener('click', (event) => {
        const link = event.target.closest('.section-next');
        if (!desktop.matches || !link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }
        const target = slides.find((slide) => '#' + slide.id === link.getAttribute('href'));
        if (target) {
            event.preventDefault();
            goToSlide(target);
        }
    });

    track.addEventListener('keydown', (event) => {
        if (!desktop.matches || !['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key) || event.altKey || event.ctrlKey || event.metaKey || event.target.closest('input, textarea, select, [contenteditable]')) {
            return;
        }
        const current = getCurrentIndex();
        const index = event.key === 'Home' ? 0 : event.key === 'End' ? slides.length - 1 : current + (event.key === 'ArrowRight' ? 1 : -1);
        if (slides[index]) {
            event.preventDefault();
            goToSlide(slides[index]);
        }
    });

    // Use vertical wheel gestures to visit the slides before leaving the portfolio.
    let lastWheel = 0;
    let lockedUntil = 0;
    window.addEventListener('wheel', (event) => {
        if (event.defaultPrevented || drag || !desktop.matches || event.ctrlKey || document.body.classList.contains('js-menuOpened') || Math.abs(event.deltaX) > Math.abs(event.deltaY) || !event.deltaY || !event.cancelable) {
            return;
        }
        // Leave nested scrolling areas and form controls in charge of their wheel events.
        if (event.target.closest('input, textarea, select, [contenteditable]')) {
            return;
        }
        for (let element = event.target; element && element !== document.body; element = element.parentElement) {
            const overflow = window.getComputedStyle(element).overflowY;
            if (element !== track && /auto|scroll/.test(overflow) && element.scrollHeight > element.clientHeight) {
                return;
            }
        }
        const rect = track.getBoundingClientRect();
        const delta = event.deltaY * (event.deltaMode === 1 ? 16 : event.deltaMode === 2 ? window.innerHeight : 1);
        const direction = Math.sign(delta);
        const current = getCurrentIndex();
        const target = slides[current + direction];
        const aligned = Math.abs(rect.top) <= 2;
        const entering = direction > 0
            ? rect.top > 0 && rect.top <= delta
            : rect.top < 0 && rect.top >= delta;
        // Long slides must remain vertically readable, including at high zoom.
        if ((!aligned && !entering) || rect.height > window.innerHeight + 4) {
            return;
        }
        const now = performance.now();
        const busy = now < lockedUntil || (lastWheel && now - lastWheel < 180);
        if (!target && !busy) {
            return;
        }
        event.preventDefault();
        lastWheel = now;
        if (busy) {
            return;
        }
        if (entering) {
            window.scrollBy({ top: rect.top, behavior: 'instant' });
        }
        lockedUntil = now + (reducedMotion.matches ? 200 : 650);
        goToSlide(target, false);
    }, { passive: false });

    desktop.addEventListener('change', () => {
        endDrag(false);
        lastWheel = 0;
        lockedUntil = 0;
        track.scrollTo({ left: 0, behavior: 'instant' });
    });
});
