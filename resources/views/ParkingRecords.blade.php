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
  <div class="actions-top" style="text-align:center; margin-bottom:20px;">
    {{-- Display error or success messages --}}
    @if ($errors->any())
      <div style="background:#7f1d1d; color:#fff; padding:10px 15px; border-radius:8px; display:inline-block; margin-bottom:10px;">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @elseif (session('ok'))
      <div style="background:#14532d; color:#fff; padding:10px 15px; border-radius:8px; display:inline-block; margin-bottom:10px;">
        {{ session('ok') }}
      </div>
    @endif

    <form class="add-form" method="POST" action="{{ route('records.store') }}" style="display:inline-flex; gap:8px;">
      @csrf
      <input type="datetime-local" name="Date_occupation" required style="padding:8px; border-radius:6px; border:none;">
      <input type="number" name="ParkerDetails_Table_Entry_id" placeholder="Parker Entry ID" required style="padding:8px; border-radius:6px; border:none;">
      <button type="submit" class="add-btn" style="background:#3b47c5; color:#fff; border:none; padding:8px 14px; border-radius:6px; cursor:pointer;">Add</button>
    </form>
  </div>

  <div class="panel">
    <table>
      <thead>
        <tr>
          <th>No.</th>
          <th>Date_occupation</th>
          <th>Parkers Details / Information</th>
        </tr>
      </thead>
      <tbody>
        @forelse($records as $r)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $r->Date_occupation }}</td>
            <td>
              <div class="parker-info" style="background:#0f1538; padding:10px 14px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.3);">
                <div style="color:#a9b5ff; font-size:13px; margin-bottom:4px;">ID: {{ $r->ParkerDetails_Table_Entry_id }}</div>
                <div style="font-weight:600; font-size:15px; margin-bottom:3px;">
                  {{ $r->parker_full_name ?? 'Unknown' }}
                  <span style="color:#a9b5ff;"> • {{ $r->parker_plate ?? 'No Plate' }}</span>
                  <span style="color:#a9b5ff;"> • {{ $r->parker_position ?? 'N/A' }}</span>
                </div>
                <div style="font-size:14px; color:#b4b8f9;">📞 {{ $r->parker_contact ?? 'No Contact' }}</div>

                <div class="parker-actions" style="margin-top:8px;">
                  <a href="{{ route('records.edit', $r->Record_ID) }}" class="btn btn-edit" 
                     style="background:#3b82f6; color:white; padding:6px 10px; border-radius:6px; text-decoration:none; margin-right:6px;">
                    Edit
                  </a>
                  <form class="inline-delete" method="POST" 
                        action="{{ route('records.destroy', $r->Record_ID) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete" 
                            style="background:#b91c1c; color:white; padding:6px 10px; border:none; border-radius:6px; cursor:pointer;">
                      Delete
                    </button>
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
