document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('tutorsContainer');
    const cards = Array.from(track.children);
    const prevBtn = document.querySelector('.carousel-nav.prev');
    const nextBtn = document.querySelector('.carousel-nav.next');
    const cardsToShow = 3;
    let currentIndex = 0;

    function updateCarousel() {
        const cardWidth = cards[0].offsetWidth + 20; // 20px gap
        const offset = currentIndex * cardWidth;
        track.style.transform = `translateX(-${offset}px)`;
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex >= cards.length - cardsToShow;
    }

    prevBtn.addEventListener('click', function () {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        }
    });

    nextBtn.addEventListener('click', function () {
        if (currentIndex < cards.length - cardsToShow) {
            currentIndex++;
            updateCarousel();
        }
    });

    // Inicializar
    updateCarousel();
});