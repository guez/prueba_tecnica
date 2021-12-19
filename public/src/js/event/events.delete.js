function deleteEvent(url_delete) {
    Swal.fire({
        title: 'Eliminar!',
        text: 'Seguro que desea eliminar este evento?',
        icon: 'warning',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'Cancelar',
        showCloseButton: true,
        showCancelButton: true,
    }).then(rs => {
        if (rs.isConfirmed) {
            axios.delete(url_delete).then(rs => {
                Toast.fire({
                    icon: 'success',
                    title: 'Se ha eliminado con éxito.'
                }).then(rs => window.location.reload());
            }).catch(err => {
                let errors = err.response.data.errors;
                if (errors != undefined) {
                    console.log(errors);
                    Object.keys(errors).forEach(keyError => {
                        Toast.fire({
                            icon: 'error',
                            title: errors[keyError]
                        })
                    });
                }
            });
        }
    });
}