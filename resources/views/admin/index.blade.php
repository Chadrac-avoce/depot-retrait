@extends('layouts.app')
@section('title','Admin')

@section('content')
<h1>Tableau de bord Admin</h1>

<h2>Utilisateurs</h2>
<table>
  <thead><tr><th>ID</th><th>Nom</th><th>Téléphone</th><th>Email</th><th>Rôle</th></tr></thead>
  <tbody>
    @foreach($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->nom }} {{ $u->prenom }}</td>
        <td>{{ $u->telephone }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<h2>Transactions</h2>
<table>
  <thead><tr><th>Réf</th><th>Utilisateur</th><th>Type</th><th>Montant</th><th>Date</th></tr></thead>
  <tbody>
    @foreach($transactions as $tr)
      <tr>
        <td>{{ $tr->reference }}</td>
        <td>{{ $tr->user->telephone }}</td>
        <td>{{ ucfirst($tr->type) }}</td>
        <td>{{ number_format($tr->montant,2,',',' ') }} FCFA</td>
        <td>{{ $tr->created_at->format('Y-m-d H:i') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
