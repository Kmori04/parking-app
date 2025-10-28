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
  <!-- Add form (TOP) -->
  <form method="POST" action="{{ route('users.store') }}" class="add-form">
    @csrf
    <input type="text" name="Full_Name" placeholder="Full Name" required>


    <input type="text" name="Id_Number" placeholder="ID Number (8 digits)" 
      required
      pattern="\d{8}"
      maxlength="8"
      inputmode="numeric"
      oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,8);">


    <input type="text" name="Contact_Number" placeholder="Contact Number (11 digits)"
      pattern="\d{11}"
      maxlength="11"
      inputmode="numeric"
      oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11);">


    <select name="Position" required>
    <option value="">Select Position</option>
    <option value="Student">Student</option>
    <option value="Guest">Guest</option>
    <option value="Professor">Professor</option>
    <option value="School Staff">School Staff</option>
    </select>



    <input type="text" name="Plate_Number" placeholder="Plate Number"
     maxlength="11"
       pattern="[A-Za-z]{1,5}\s[0-11]{0,5}"
       oninput="
         // Allow only letters (A–Z) then space then digits
         let val = this.value.toUpperCase().replace(/[^A-Z0-9 ]/g, '');
         // Enforce a single space between letters and digits
         val = val.replace(/^([A-Z]{0,5})(\s?)([0-9]{0,5}).*$/, (_, l, s, n) => 
           l + (n.length > 0 ? ' ' : '') + n
         );
         this.value = val;">


    <select name="Vehicle_Type" required>
      <option value="">Vehicle Type</option>
      <option value="Car">Car</option>
      <option value="Motorcycle">Motorcycle</option>
    </select>

    <select name="Department" required>
      <option value="">Select Department</option>
      <option value="CCS">CCS</option>
      <option value="COA">COA</option>
      <option value="CBA">CBA</option>
      <option value="CASED">CASED</option>
      <option value="CON">CON</option>
      <option value="COE">COE</option>
      <option value="—">—</option>
    </select>

    <select name="Parking_counts" required>
      <option value="">Select Parking Count</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="No Parking Limit">No Parking Limit</option>
      <option value="Temporary Parking">Temporary Parking</option>
    </select>


    <button type="submit" class="add-btn">Add</button>
  </form>

  <!-- Search form (BELOW) -->
  <form method="GET" action="{{ route('users.index') }}" class="search-form">
    <input type="text" name="q" placeholder="Search user..." value="{{ request('q') }}">
    <button type="submit" class="search-btn">Search</button>
    @if(request('q'))
      <a href="{{ route('users.index') }}" class="clear-link">Clear</a>
    @endif
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
