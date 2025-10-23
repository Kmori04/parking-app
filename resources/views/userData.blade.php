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
    <h1 class="page-title">Registered Parkers Information</h1>

    {{-- success message after update --}}
    @if(session('ok'))
      <div class="flash success">{{ session('ok') }}</div>
    @endif

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
              <th class="actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($userData as $user)
              <tr>
                <td>{{ $user->Entry_id }}</td>
                <td>{{ $user->Full_Name ?? '—' }}</td>
                <td>{{ $user->Id_Number }}</td>
                <td>{{ $user->Contact_Number }}</td>
                <td>{{ $user->Position }}</td>
                <td>{{ $user->Plate_Number }}</td>
                <td>{{ $user->Vehicle_Type }}</td>
                <td>{{ $user->Department ?? '—' }}</td>
                <td>
                  <span class="badge {{ $user->status_class ?? '' }}">
                    {{ $user->status_label ?? ($user->Parking_counts ?? '—') }}
                  </span>
                </td>
                <td class="actions">
                  <a class="btn btn-edit" href="{{ route('users.edit', $user->Entry_id) }}">Edit</a>
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
