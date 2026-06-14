<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'LifeFlow' }}</title>
    <link rel="manifest" href="/manifest.json">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="/assets/app.css" rel="stylesheet">
</head>
<body class="auth-body">
    <main class="auth-wrap">
        <section class="auth-card">
            <div class="brand-mark mx-auto mb-3">LF</div>
            {{ $slot ?? '' }}
            @yield('content')
        </section>
    </main>
</body>
</html>
