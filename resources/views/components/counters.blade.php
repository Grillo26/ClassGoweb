<div class="counters">
    <div class="counter-box">
        <div class="counter-number" data-target="500">+0</div>
        <h1>Usuarios registrados</h1>
    </div>
    <div class="box-sky"></div>
    <div class="counter-box">
        <div class="counter-number" data-target="230">+0</div>
        <h1>Tutores disponibles</h1>
    </div>
    <div class="box-sky"></div>
    <div class="counter-box">
        <div class="counter-number" data-target="230">0</div>
        <h1>Estudiantes registrados</h1>
    </div>
    <div class="box-sky"></div>
    <div class="counter-box">
        <div class="counter-numbe"><i class="fa fa-star"></i>4.5</div>
        <h1>En Play Store</h1>
    </div>
</div> 
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll('.counter-number');

    const animateCounter = (el) => {
        const target = +el.getAttribute('data-target');
        const isDecimal = el.getAttribute('data-decimal') === 'true';
        let count = 0;
        const step = isDecimal ? 0.1 : Math.ceil(target / 100);

        const updateCounter = () => {
            count += step;
            if (count < target) {
                el.innerHTML = isDecimal ? `<i class="fa fa-star"></i> ${count.toFixed(1)}` : `+${Math.floor(count)}`;
                requestAnimationFrame(updateCounter);
            } else {
                el.innerHTML = isDecimal ? `<i class="fa fa-star"></i> ${target.toFixed(1)}` : `+${target}`;
            }
        };

        updateCounter();
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.6 });

    counters.forEach(counter => observer.observe(counter));
});
</script>