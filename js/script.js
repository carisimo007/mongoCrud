// Confirmar para eliminar libro
function confirmarEliminar(id) {
    if(confirm("¿Estás seguro de eliminar este libro?")) {
        window.location.href = 'php/delete.php?id=' + id;
    }
}

// Confirmar antes de agregar un libro
document.addEventListener('DOMContentLoaded', () => {
    // Selecciona el formulario de agregar libro por acción
    const formAgregar = document.querySelector('form[action="php/create.php"]');

    if (formAgregar) {
        formAgregar.addEventListener('submit', function(event) {
            const confirmado = confirm("¿Estás seguro que quieres agregar este libro?");
            if (!confirmado) {
                event.preventDefault(); // Cancela el envío del formulario
            }
        });
    }
});