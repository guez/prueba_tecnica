function deleteEvent(url_delete) {
    Swal.fire({
        title: 'Eliminar!',
        text: 'Seguro que desea eliminar este evento?',
        icon: 'warning',
        confirmButtonText: 'Si, Eliminar',
        cancelButton: 'Cancelar'
    }).then(rs => {
        if (rs.isConfirmed) {
            axios.delete(url_delete).then(rs => {
                Toast.fire({
                    icon: 'success',
                    title: 'Se ha eliminado con éxito.'
                }).then(rs=>window.location.reload());
            }).catch(err => console.log(err));
        }
    });
}