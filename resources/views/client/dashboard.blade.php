@extends('layouts.app')
@section('title','Dashboard')

@section('content')
<h1>Bienvenue, {{ $user->prenom ?? $user->nom }}</h1>

<div class="card">
  <h3>Solde actuel</h3>
  <p class="big">{{ number_format($wallet->solde,2,',',' ') }} FCFA</p>
</div>

<div class="card">
  <h3>Faire un dépôt</h3>
  <form method="POST" action="{{ route('depot') }}">
    @csrf
    <label>Montant :
      <input type="number" name="montant" required min="1">
    </label>
    <button type="submit">Déposer</button>
  </form>
</div>

<div class="card">
  <h3>Faire un retrait</h3>
  <form method="POST" action="{{ route('retrait') }}">
    @csrf
    <label>Montant :
      <input type="number" name="montant" required min="1">
    </label>
    <button type="submit">Retirer</button>
  </form>
</div>

<div class="card">
  <h3>Transférer à un autre utilisateur</h3>
  <form method="POST" action="{{ route('transfert') }}">
    @csrf
    <label>Téléphone du destinataire :
      <input type="text" name="telephone_dest" id="telephone_dest" required>
      <span id="destinataire_nom" style="margin-left:10px;color:blue;"></span>
    </label>
    <label>Montant :
      <input type="number" name="montant" required min="1">
    </label>
    <button type="submit">Transférer</button>
  </form>
</div>

<h2>Historique des transactions</h2>
<table>
  <thead>
    <tr><th>Référence</th><th>Type</th><th>Montant</th><th>Destinataire</th><th>Date</th></tr>
  </thead>
  <tbody>
    @foreach($transactions as $t)
      <tr>
        <td>{{ $t->reference }}</td>
        <td>{{ ucfirst($t->type) }}</td>
        <td>{{ number_format($t->montant,2,',',' ') }} FCFA</td>
        <td>
          @if($t->destinataire_id)
            {{ \App\Models\User::find($t->destinataire_id)->prenom ?? \App\Models\User::find($t->destinataire_id)->nom ?? 'Inconnu' }}
          @else
            -
          @endif
        </td>
        <td>{{ $t->created_at->format('Y-m-d H:i') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<script>
document.getElementById('telephone_dest').addEventListener('input', function() {
    let telephone = this.value.trim();
    let span = document.getElementById('destinataire_nom');

    if (telephone.length > 0) {
        fetch('/check-user/' + telephone)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    span.textContent = 'Destinataire: ' + data.name;
                } else {
                    span.textContent = 'Destinataire non trouvé (sera créé automatiquement)';
                }
            });
    } else {
        span.textContent = '';
    }
});
</script>
@endsection
