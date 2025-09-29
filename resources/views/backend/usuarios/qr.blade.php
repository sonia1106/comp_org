@extends('backend.index')
@section('contenido')
<div class="container text-center mt-5">

    <h5 class="mb-4">Código QR de tu compra</h5>

    {{-- Mostrar QR directamente --}}
    <div class="mb-3">
        {!! $qr !!}
    </div>

    {{-- Mostrar total --}}
    <p class="mt-3"><strong>Total:</strong> ${{ number_format($totalGeneral, 2) }}</p>

    @php
        $telefono = auth()->user()->telefono ?? '59170000000'; // Ajusta según tu campo
        $mensaje = urlencode("Hola, este es tu QR para confirmar tu compra.\n\nTotal: $" . number_format($totalGeneral, 2));
        $url = "https://wa.me/$telefono?text=$mensaje";
    @endphp

    {{-- Botón de WhatsApp --}}
    <a href="{{ $url }}" target="_blank" class="btn btn-success mt-3">
        <i class="bi bi-whatsapp"></i> Enviar por WhatsApp
    </a>
    <form action="{{ route('compras.pagoConfirmado') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-primary">
            Ya realicé el pago
        </button>
    </form>


</div>
@endsection
