<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Parking System</title>

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <h2>WELCOME TO COLUMBAN COLLEGE</h2>
        <nav>
             <a class="nav-btn" href="{{ route('userData') }}">User Data</a>
            <a class="nav-btn" href="{{ route('availability') }}">Parking Availability</a>
            <a class="nav-btn" href="{{ route('records.index') }}">Parking Records</a>

        </nav>
    </header>

    <main>
        <div class="text-section">
            <h1>CAMPUS<br>PARKING<br>SYSTEM</h1>
        </div>

            <img src="{{ asset('LOGO.webp') }}" alt="Logo" class="logo-image">
        
    </main>
</body>
</html>