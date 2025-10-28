<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Parker</title>
  @vite(['resources/css/userData.css', 'resources/js/userData.js'])

  <style>
  .form-card {
    background: #0f1f4a66;
    border: 1px solid #384a86;
    border-radius: 12px;
    padding: 20px;
    margin-top: 16px;
  }
  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }
  .form-row + .form-row {
    margin-top: 12px;
  }
  label {
    font-weight: 700;
    font-size: .9rem;
  }

  /* Base styles for all inputs and selects */
  input:not([type="submit"]),
  select {
    width: 100%;
    padding: .6rem .7rem;
    border-radius: .6rem;
    border: 1px solid #33406d;
    background-color: #0f1f4a66; /* default background */
    color: #ffffffff;
    transition: background-color 0.25s ease, color 0.25s ease;
  }

  /* Gray background when empty or unselected */
  input:not([type="submit"]):placeholder-shown,
  select:invalid {
    background-color: #0f1f4a66; /* gray for empty fields */
    color: #6d6d6dff;
  }

  select {
      background-color: #0f1f4a66;
  color: #ffffffff;
  border: 1px solid #33406d;
  outline: none;
  }

  select option {
  background-color: #0f1f4a66;
  color: #ffffffff;
}

/* Hover state for dropdown options */
select option:hover,
select option:focus,
select:focus option:checked {
  background-color: gray;
  color: #ffffffff;
}

  /* Buttons */
  .actions {
    margin-top: 16px;
    display: flex;
    gap: 10px;
  }
  .btn {
    display: inline-block;
    padding: .55rem .9rem;
    border-radius: .6rem;
    font-weight: 700;
    text-decoration: none;
  }
  .btn-save {
    background: #2b74ff;
    color: #fff;
  }
  .btn-cancel {
    background: #20284e;
    color: #cfd8ff;
    border: 1px solid #3b4a84;
  }


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
            <input name="Full_Name" placeholder=" " value="{{ old('Full_Name', $user->Full_Name) }}" required>
          </div>
          <div>
            <label>ID Number</label>
            <input name="Id_Number" placeholder=" " value="{{ old('Id_Number', $user->Id_Number) }}" required>
          </div>
        </div>

        <div class="form-row">
          <div>
            <label>Contact Number</label>
            <input name="Contact_Number" placeholder=" " value="{{ old('Contact_Number', $user->Contact_Number) }}">
          </div>
          <div>
            <label>Position</label>
            @php $p = old('Position', $user->Position); @endphp
            <select name="Position" required>
              <option value="">Select Position</option>
              <option value="Student" {{ $p==='Student'?'selected':'' }}>Student</option>
              <option value="Guest" {{ $p==='Guest'?'selected':'' }}>Guest</option>
              <option value="Professor" {{ $p==='Professor'?'selected':'' }}>Professor</option>
              <option value="School Staff" {{ $p==='School Staff'?'selected':'' }}>School Staff</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div>
            <label>Plate Number</label>
            <input name="Plate_Number" placeholder=" " value="{{ old('Plate_Number', $user->Plate_Number) }}">
          </div>
          <div>
            <label>Vehicle Type</label>
            @php $v = old('Vehicle_Type', $user->Vehicle_Type); @endphp
            <select name="Vehicle_Type" required>
              <option value="">Select Vehicle Type</option>
              <option value="Car" {{ $v==='Car'?'selected':'' }}>Car</option>
              <option value="Motorcycle" {{ $v==='Motorcycle'?'selected':'' }}>Motorcycle</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div>
            <label>Department</label>
            @php $d = old('Department', $user->Department); @endphp
            <select name="Department" required>
              <option value="">Select Department</option>
              <option value="CCS" {{ $d==='CCS'?'selected':'' }}>CCS</option>
              <option value="COA" {{ $d==='COA'?'selected':'' }}>COA</option>
              <option value="CBA" {{ $d==='CBA'?'selected':'' }}>CBA</option>
              <option value="CASED" {{ $d==='CASED'?'selected':'' }}>CASED</option>
              <option value="CON" {{ $d==='CON'?'selected':'' }}>CON</option>
              <option value="COE" {{ $d==='COE'?'selected':'' }}>COE</option>
              <option value="—" {{ $d==='—'?'selected':'' }}>—</option>
            </select>
          </div>

          <div>
            <label>Parking Counts (number or text)</label>
            @php $pc = old('Parking_counts', $user->Parking_counts); @endphp
            <select name="Parking_counts" required>
              <option value="">Select Parking Count</option>
              <option value="1" {{ $pc==='1'?'selected':'' }}>1</option>
              <option value="2" {{ $pc==='2'?'selected':'' }}>2</option>
              <option value="No Parking Limit" {{ $pc==='No Parking Limit'?'selected':'' }}>No Parking Limit</option>
              <option value="Temporary Parking" {{ $pc==='Temporary Parking'?'selected':'' }}>Temporary Parking</option>
            </select>
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
