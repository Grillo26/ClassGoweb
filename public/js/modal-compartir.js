<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalCompartir');
    const abrirBtn = document.getElementById('abrirModal');
    const cerrarBtn = document.getElementById('cerrarModal');

    abrirBtn.addEventListener('click', () => {
        modal.classList.add('active');
    });

    cerrarBtn.addEventListener('click', () => {
        modal.classList.remove('active');
    });

    // Cierre al hacer clic fuera del contenido
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
});
</script>
