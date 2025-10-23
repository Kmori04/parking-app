<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Parker</title>
  @vite(['resources/css/userData.css', 'resources/js/userData.js'])
  <style>
    .form-card{background:#0f1f4a66;border:1px solid #384a86;border-radius:12px;padding:20px;margin-top:16px}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    .form-row + .form-row{margin-top:12px}
    label{font-weight:700;font-size:.9rem}
    input,select{width:100%;padding:.6rem .7rem;border-radius:.6rem;border:1px solid #33406d;background:#0c1b3f;color:#fff}
    .actions{margin-top:16px;display:flex;gap:10px}
    .btn{display:inline-block;padding:.55rem .9rem;border-radius:.6rem;font-weight:700;text-decoration:none}
    .btn-save{background:#2b74ff;color:#fff}
    .btn-cancel{background:#20284e;color:#cfd8ff;border:1px solid #3b4a84}
  </style>
</head>
<body>
  <header>
    <a href="{{ route('users.index') }}" class="back-btn">⬅ Back</a>
  </header>

  <main class="container">
    <h1 class="page-title">Edit Parker</h1>

    @if ($errors->any())
      <div class="flash error">
        <ul>
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="form-card">
      <form method="POST" action="{{ route('users.update', $user->Entry_id) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
          <div>
            <label>Full Name</label>
            @php
              // Robust fallback: supports model attr "Full_name" OR column "Full Name"
              $fullNameValue = old(
                'Full_name',
                $user->Full_name ?? ($user->{'Full Name'} ?? null)
              );
            @endphp
            <input name="Full_name" value="{{ $fullNameValue }}" required>
          </div>
          <div>
            <label>ID Number</label>
            <input name="Id_Number" value="{{ old('Id_Number', $user->Id_Number) }}" required>
          </div>
        </div>

        <div class="form-row">
          <div>
            <label>Contact Number</label>
            <input name="Contact_Number" value="{{ old('Contact_Number', $user->Contact_Number) }}">
          </div>
          <div>
            <label>Position</label>
            <input name="Position" value="{{ old('Position', $user->Position) }}">
          </div>
        </div>

        <div class="form-row">
          <div>
            <label>Plate Number</label>
            <input name="Plate_Number" value="{{ old('Plate_Number', $user->Plate_Number) }}">
          </div>
          <div>
            <label>Vehicle Type</label>
            @php $v = old('Vehicle_Type', $user->Vehicle_Type); @endphp
            <select name="Vehicle_Type" required>
              <option value="Car" {{ $v==='Car'?'selected':'' }}>Car</option>
              <option value="Motorcycle" {{ $v==='Motorcycle'?'selected':'' }}>Motorcycle</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div>
            <label>Department</label>
            <input name="Department" value="{{ old('Department', $user->Department) }}">
          </div>
          <div>
            <label>Parking Counts (number or text)</label>
            <input name="Parking_counts" value="{{ old('Parking_counts', $user->Parking_counts) }}" placeholder='e.g. 2 or "No Parking Limit"'>
          </div>
        </div>

        <div class="actions">
          <button class="btn btn-save" type="submit">Save</button>
          <a class="btn btn-cancel" href="{{ route('users.index') }}">Cancel</a>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
