<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Space Grotesk", sans-serif;
            background: linear-gradient(135deg, #081018 0%, #142437 100%);
            color: #e8f2fb;
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 420px;
            padding: 24px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(10, 20, 32, 0.92);
        }
        h1 { margin: 0 0 8px; }
        p { margin: 0 0 16px; color: #9bb1c4; }
        label { display: block; margin: 10px 0 6px; font-weight: 600; }
        input {
            width: 100%;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.15);
            background: #0f1d2a;
            color: #e8f2fb;
        }
        button {
            margin-top: 14px;
            width: 100%;
            padding: 11px 12px;
            border: none;
            border-radius: 10px;
            background: #ff8a3d;
            color: #1e1308;
            font-weight: 700;
            cursor: pointer;
        }
        .error {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255, 93, 115, 0.16);
            border: 1px solid rgba(255, 93, 115, 0.45);
        }
    </style>
</head>
<body>
    <form class="card" method="POST" action="{{ route('admin.login') }}">
        @csrf
        <h1>Login Admin</h1>
        <p>Masuk untuk mengelola data peminjaman alat.</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Masuk</button>
    </form>
</body>
</html>
