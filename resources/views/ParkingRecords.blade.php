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

  {{-- ===== Add New Record Section ===== --}}
  <div class="actions-top">
    <form class="add-form" method="POST" action="{{ route('records.store') }}">
      @csrf
      <input type="datetime-local" name="Date_occupation" required>
      <input type="number" name="ParkerDetails_Table_Entry_id" placeholder="Parker Entry_id" required>
      <button type="submit" class="add-btn">Add</button>
    </form>
  </div>

  {{-- ===== Flash Message ===== --}}
  @if(session('ok'))
    <div class="flash success">{{ session('ok') }}</div>
  @endif

  {{-- ===== Records Table ===== --}}
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
            <td>
              <div class="parker-info">
                <div class="parker-id">ID: {{ $r->ParkerDetails_Table_Entry_id }}</div>
                <div class="parker-main">
                  {{ $r->parker_full_name ?? 'Unknown' }}
                  <span>• {{ $r->parker_plate ?? 'No Plate' }}</span>
                  <span>• {{ $r->parker_position ?? 'N/A' }}</span>
                </div>
                <div class="parker-contact">
                  📞 {{ $r->parker_contact ?? 'No Contact' }}
                </div>
                <div class="parker-actions">
                  <a href="{{ route('records.edit', $r->Record_ID) }}" class="btn btn-edit">Edit</a>
                  <form class="inline-delete" method="POST" action="{{ route('records.destroy', $r->Record_ID) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">Delete</button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="no-data">No records found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</body>
</html>
