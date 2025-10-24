@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px; margin: auto; padding: 20px;">

    <h2 style="text-align: center; margin-bottom: 20px;">Transférer de l'argent</h2>

    <!-- Messages -->
    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulaire de transfert -->
    <form action="{{ route('transfer.submit') }}" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
        @csrf

        <div>
            <label for="recipient_phone">Téléphone du destinataire :</label>
            <input type="text" id="recipient_phone" name="recipient_phone" required
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div>
            <label for="amount">Montant :</label>
            <input type="number" id="amount" name="amount" min="1" required
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit" 
                style="padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Transférer
        </button>
    </form>
</div>
@endsection
