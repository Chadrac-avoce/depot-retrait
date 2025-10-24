<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'DepotRetrait')</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <header class="topbar">
    <nav>
      @auth
        <a href="{{ route('dashboard') }}">Dashboard</a>
        @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.index') }}">Admin</a>
        @endif
        <a href="{{ route('logout') }}">Déconnexion</a>
      @else
        <a href="{{ route('login') }}">Connexion</a>
        <a href="{{ route('register') }}">Inscription</a>
      @endauth
    </nav>
  </header>

  <main class="container">
    @if(session('success')) 
      <div class="alert success">{{ session('success') }}</div> 
    @endif

    @if($errors->any()) 
      <div class="alert error">{{ $errors->first() }}</div> 
    @endif

    @yield('content')
  </main>
</body>
</html>
