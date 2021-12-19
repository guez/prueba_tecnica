function openModalBuyTickets(eventId, eventName, categoryName) {
    let buyTicketModal = new bootstrap.Modal(document.getElementById('BuyTicketModal'), {})
    document.getElementById("eventId").value = eventId;
    document.getElementById("eventName").value = eventName;
    document.getElementById("categoryName").value = categoryName;
    buyTicketModal.show();
}


function buyTicketsNow(event) {
    event.preventDefault();

    let eventId = document.getElementById("eventId").value;
    let amount = document.getElementById("amount").value;
    let email = document.getElementById("email").value;

    let formData = {
        "amount": amount,
        "email": email,
        "event_id": eventId,
    };

    Swal.fire({
        title: 'Compra de Entradas!',
        html: `Seguro que desea comprar ${amount} para este evento?<br>Recibirá un correo confirmando su compra a <b>"${email}"</b>`,
        icon: 'success',
        confirmButtonText: 'Si, Comprar',
        cancelButtonText: 'Cancelar',
        showCloseButton: true,
        showCancelButton: true,
    }).then(rs => {
        if (rs.isConfirmed) {
            axios.post(event.target.action, formData).then(rs => {
                console.log(rs);
                Toast.fire({
                    icon: 'success',
                    title: 'Ha realizado la compra con éxito.'
                    }).then(rs => window.location.reload());
                // })
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
    return false;
}



