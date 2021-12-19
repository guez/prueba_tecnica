<!-- Modal -->
<form onsubmit="buyTicketsNow(event)" action="{{ route("events_assistants.store") }}">
    <div class="modal fade" id="BuyTicketModal" tabindex="-1" aria-labelledby="BuyTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="BuyTicketModalLabel"><i class="fa fa-ticket-alt" style="color: #555;"></i>
                        Comprar Entradas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Código de Evento:</label>
                            <input class="form-control" id="eventId" readonly>
                        </div>
                        <div class="col-sm-4">
                            <label>Nombre:</label>
                            <input class="form-control" id="eventName" readonly>
                        </div>
                        <div class="col-sm-4">
                            <label>Categoría:</label>
                            <input class="form-control" id="categoryName" readonly>
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-sm-8">
                            <label>Correo Electrónico:</label>
                            <input type="email" required class="form-control" id="email">
                        </div>
                        <div class="col-sm-4">
                            <label>Cantidad de Entradas:</label>
                            <input type="number" required class="form-control" value="1" id="amount">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Comprar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</form>
