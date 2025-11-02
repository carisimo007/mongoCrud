// Confirmar antes de eliminar
function confirmarEliminar(id) {
    if(confirm('¿Desea eliminar este libro?')) {
        window.location.href = 'php/delete.php?id=' + id;
    }
}

// Confirmar antes de agregar
document.addEventListener('DOMContentLoaded', () => {
    const formAgregar = document.getElementById('formAgregar');
    if (formAgregar) {
        formAgregar.addEventListener('submit', function(e) {
            if (!confirm("¿Estás seguro que quieres agregar este libro?")) {
                e.preventDefault();
            }
        });
    }
});
