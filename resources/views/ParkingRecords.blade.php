<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Parking Records</title>
  @vite(['resources/css/parking_records.css'])
</head>
<body>
 <header class="bar">
    <a href="{{ route('home') }}" class="icon-left" aria-label="Home">🏠</a>
    <h1>Parking Records</h1>
  </header>

    <div class="panel">
      <table>
        <thead>
          <tr>
            <th>Record_ID</th>
            <th>Date_occupation</th>
            <th>Parkers Details / Information</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $r)
            <tr>
              <td>{{ $r->Record_ID }}</td>
              <td>{{ $r->Date_occupation }}</td>
              <td>{{ $r->ParkerDetails_Table_Entry_id }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="3">No records found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
