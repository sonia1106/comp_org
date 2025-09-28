@extends('backend.index')
@section('contenido')
<div class="container text-center mt-5">
    <h2>Confirmación de compra</h2>
    <p>Escanea el siguiente código QR para realizar el pago.</p>

    <!-- Botón para abrir modal -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#qrModal">
        Ver QR de Pago
    </button>

    <!-- Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrModalLabel">Código QR de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="data:image/png;base64,{{ $qr }}" alt="QR de pago" class="img-fluid mb-3">
                    <p><strong>Total:</strong> ${{ number_format($totalGeneral, 2) }}</p>
                </div>
                <div class="modal-footer">
                    <!-- Botón para enviar por WhatsApp -->
                    @php
                        $numero = auth()->user()->telefono ?? '59170000000'; 
                        $mensaje = urlencode("Hola " . auth()->user()->name . ", aquí está tu QR de pago por un total de $" . number_format($totalGeneral, 2));
                        $link = "https://wa.me/$numero?text=$mensaje";
                    @endphp
                    <a href="{{ $link }}" target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> Enviar por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
