<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Data</title>
  @vite(['resources/css/userData.css', 'resources/js/userData.js'])
</head>
<body>
  <header>
    <a href="{{ route('home') }}" class="back-btn">⬅ Back</a>
  </header>

  <main class="container">
    <h1 class="page-title">Registered ParkersHAHAHA</h1>

    <section class="panel">
      <h2>All Registered Users</h2>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Entry ID</th>
              <th>Full Name</th>
              <th>ID Number</th>
              <th>Contact Number</th>
              <th>Position</th>
              <th>Plate Number</th>
              <th>Vehicle Type</th>
              <th>Department</th>
              <th>Parking Counts</th>
            </tr>
          </thead>
          <tbody>
            @foreach($userData as $user)
              <tr>
                <td>{{ $user->Entry_id }}</td>
                <td>{{ $user->Full_name }}</td>
                <td>{{ $user->Id_Number }}</td>
                <td>{{ $user->Contact_Number }}</td>
                <td>{{ $user->Position }}</td>
                <td>{{ $user->Plate_Number }}</td>
                <td>{{ $user->Vehicle_Type }}</td>
                <td>{{ $user->Department ?? '—' }}</td>
                <td>
                  @if(strtolower($user->Parking_counts) === 'no parking limit')
                    <span class="badge unlimited">No Parking Limit</span>
                  @elseif(strtolower($user->Parking_counts) === 'temporary parking')
                    <span class="badge temp">Temporary Parking</span>
                  @else
                    <span class="badge count">{{ $user->Parking_counts }}</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>
