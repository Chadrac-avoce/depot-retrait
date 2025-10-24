@extends('layouts.app')
@section('title','Connexion')

@section('content')
<h1>Connexion</h1>
<form method="POST" action="{{ url('/login') }}">
  @csrf
  <label>Téléphone :
    <input type="text" name="telephone" required value="{{ old('telephone') }}">
  </label>
  <label>Mot de passe :
    <input type="password" name="password" required>
  </label>
  <button type="submit">Se connecter</button>
</form>
<p>Pas de compte ? <a href="{{ route('register') }}">Inscription</a></p>
@endsection
