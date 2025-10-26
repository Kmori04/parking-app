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

    {{-- ----- [ADD] begin: Add button + form (right-aligned toolbar) ----- --}}
    <div class="actions-top">
      <form method="POST" action="{{ route('users.store') }}" class="add-form">
        @csrf
        <input type="text" name="Full_Name" placeholder="Full Name" required>
        <input type="text" name="Id_Number" placeholder="ID Number" required>
        <input type="text" name="Contact_Number" placeholder="Contact Number">
        <input type="text" name="Position" placeholder="Position">
        <input type="text" name="Plate_Number" placeholder="Plate Number">
        <select name="Vehicle_Type" required>
          <option value="">Select Type</option>
          <option value="Car">Car</option>
          <option value="Motorcycle">Motorcycle</option>
        </select>
        <input type="text" name="Department" placeholder="Department">
        <input type="text" name="Parking_counts" placeholder="Parking Counts">
        <button type="submit" class="add-btn">Add</button>
      </form>
    </div>
    {{-- ----- [ADD] end ----- --}}

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

                  {{-- ----- [ADD] begin: Delete action ----- --}}
                  <form method="POST"
                        action="{{ route('users.destroy', $user->Entry_id) }}"
                        onsubmit="return confirm('Delete this user?');"
                        style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">Delete</button>
                  </form>
                  {{-- ----- [ADD] end ----- --}}
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
