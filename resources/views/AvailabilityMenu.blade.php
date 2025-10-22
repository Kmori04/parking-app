<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parking Availability</title>
  @vite(['resources/css/AvailableMenu.css', 'resources/js/app.js'])
</head>
<body>
  <header class="bar">
    <a href="{{ route('home') }}" class="icon-left" aria-label="Home">🏠</a>
    <h1>Parking Availability</h1>
  </header>
  <div class="divider"></div>

  <main class="center">
     <a href="{{ route('availability.cars') }}" class="big-btn">CARS</a>
    <a href="{{ route('availability.motors') }}" class="big-btn">MOTORS</a>
    <a href="#" class="big-btn">VIP</a>
  </main>
</body>
</html>