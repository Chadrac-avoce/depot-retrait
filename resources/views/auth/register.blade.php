@extends('layouts.app')
@section('title','Inscription')

@section('content')
<h1>Inscription</h1>
<form method="POST" action="{{ url('/register') }}">
  @csrf
  <label>Nom :
    <input type="text" name="nom" required>
  </label>
  <label>Prénom :
    <input type="text" name="prenom" required>
  </label>
  <label>Téléphone :
    <input type="text" name="telephone" required>
  </label>
  <label>Email :
    <input type="email" name="email">
  </label>
  <label>Mot de passe :
    <input type="password" name="password" required>
  </label>
  <label>Confirmer le mot de passe :
    <input type="password" name="password_confirmation" required>
  </label>
  <button type="submit">S'inscrire</button>
</form>
<p>Déjà inscrit ? <a href="{{ route('login') }}">Connexion</a></p>
@endsection
