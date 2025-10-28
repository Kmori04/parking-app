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

  {{-- Flash & Errors --}}
  <div style="max-width:1000px;margin:16px auto 0;padding:0 20px;">
    @if (session('ok'))
      <div style="background:#14532d;color:#fff;padding:10px 14px;border-radius:8px;margin-bottom:12px;">
        {{ session('ok') }}
      </div>
    @endif

    @if ($errors->any())
      <div style="background:#7f1d1d;color:#fff;padding:10px 14px;border-radius:8px;margin-bottom:12px;">
        @foreach ($errors->all() as $e)
          <div>{{ $e }}</div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Add new record --}}
  <div style="max-width:1000px;margin:0 auto 10px;padding:0 20px;display:flex;justify-content:flex-end;gap:10px;">
    <form method="POST" action="{{ route('records.store') }}" style="display:flex;gap:10px;align-items:center;">
      @csrf
      <input type="datetime-local"
             name="Date_occupation"
             required
             style="padding:10px;border-radius:8px;border:1px solid #2a3676;background:#0f1538;color:#fff;">
      <input type="number"
             name="ParkerDetails_Table_Entry_id"
             placeholder="Parker Entry ID"
             min="1"
             required
             style="padding:10px;border-radius:8px;border:1px solid #2a3676;background:#0f1538;color:#fff;width:180px;">
      <button type="submit"
              style="background:#3b82f6;color:#fff;border:none;padding:10px 14px;border-radius:8px;font-weight:700;cursor:pointer;">
        Add
      </button>
    </form>
  </div>

  {{-- Records Table --}}
  <div class="panel">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th style="width:80px;">No.</th>
            <th>Date_occupation</th>
            <th>Parkers Details / Information</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $r)
            <tr>
              {{-- Sequential number instead of Record_ID --}}
              <td>{{ $loop->iteration }}</td>

              {{-- Inline date edit form --}}
              <td>
                <form method="POST" action="{{ route('records.update', $r->Record_ID) }}" style="display:flex;gap:10px;align-items:center;">
                  @csrf
                  @method('PUT')
                  <input type="datetime-local"
                         name="Date_occupation"
                         value="{{ \Carbon\Carbon::parse($r->Date_occupation)->format('Y-m-d\TH:i') }}"
                         style="padding:8px;border-radius:8px;border:1px solid #2a3676;background:#0f1538;color:#fff;">
                  <button type="submit"
                          style="background:#2563eb;color:#fff;border:none;padding:8px 12px;border-radius:8px;font-weight:700;cursor:pointer;">
                    Save
                  </button>
                </form>
              </td>

              {{-- Parker details --}}
              <td>
                <div style="background:#0f1538;border:1px solid #263270;border-radius:10px;padding:12px;display:flex;justify-content:space-between;align-items:center;gap:14px;">
                  <div>
                    <div style="color:#a9b5ff;font-weight:700;margin-bottom:4px;">ID: {{ $r->ParkerDetails_Table_Entry_id }}</div>
                    @isset($r->parker_info)
                      {!! $r->parker_info !!}
                    @endisset
                  </div>

                  <form method="POST" action="{{ route('records.destroy', $r->Record_ID) }}"
                        onsubmit="return confirm('Delete this record?')" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            style="background:#b91c1c;color:#fff;border:none;padding:8px 12px;border-radius:8px;font-weight:700;cursor:pointer;">
                      Delete
                    </button>
                  </form>
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
  </div>
</body>
</html>
