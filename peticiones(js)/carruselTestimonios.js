document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('#myVerticalCarousel');
    if (!container) return;

    const carousel = container.querySelector('.my-carousel-wrapper');
    const items = Array.from(container.querySelectorAll('.my-carousel-item'));
    const prevBtn = container.querySelector('.my-carousel-btn.prev');
    const nextBtn = container.querySelector('.my-carousel-btn.next');
    const controls = container.querySelector('.my-carousel-controls');

    if (!carousel || items.length === 0) return;

    if (items.length <= 1 && controls) controls.style.display = 'none';

    let currentIndex = 0;
    let direction = 1;
    const intervalTime = 3000;
    let autoSlide = null;

    function updateCarousel() {
        carousel.style.transform = `translateY(-${currentIndex * 100}%)`;
    }

    function startAuto() {
        stopAuto();
        if (items.length <= 1) return;
        autoSlide = setInterval(() => {
            currentIndex += direction;

            if (currentIndex >= items.length) {
                currentIndex = items.length - 2;
                direction = -1;
            } else if (currentIndex < 0) {
                currentIndex = 1;
                direction = 1;
            }

            updateCarousel();
        }, intervalTime);
    }

    function stopAuto() {
        if (autoSlide) {
            clearInterval(autoSlide);
            autoSlide = null;
        }
    }

    function resetAuto() {
        startAuto();
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentIndex = Math.max(0, currentIndex - 1);
            direction = -1;
            updateCarousel();
            resetAuto();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentIndex = Math.min(items.length - 1, currentIndex + 1);
            direction = 1;
            updateCarousel();
            resetAuto();
        });
    }

    container.addEventListener('mouseenter', stopAuto);
    container.addEventListener('mouseleave', startAuto);

    function setupSeeMore() {
        const seeBtns = container.querySelectorAll('.see-more');
        seeBtns.forEach(btn => {
            btn.setAttribute('role', 'button');
            btn.setAttribute('aria-expanded', 'false');

            btn.addEventListener('click', () => {
                const text = btn.previousElementSibling;
                if (!text) return;

                const expanded = text.classList.toggle('expanded');
                btn.textContent = expanded ? 'Ver menos' : 'Ver más';
                btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');

                if (expanded) stopAuto();
                else resetAuto();
            });
        });
    }

    setupSeeMore();

    updateCarousel();
    startAuto();
});
