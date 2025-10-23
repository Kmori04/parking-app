<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit User</title>
  @vite(['resources/css/userData.css'])
</head>
<body>
  <header>
    <a href="{{ route('userData') }}" class="back-btn">⬅ Back</a>
  </header>

  <main class="container">
    <h1 class="page-title">Edit User</h1>

    @if($errors->any())
      <div class="panel" style="margin-bottom:14px;background:#5c1d1d;border-color:#862c2c">
        <strong>Fix the following:</strong>
        <ul style="margin-top:6px;margin-left:16px">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('userData.update', $user->Entry_id) }}" method="POST" class="panel" style="display:grid;gap:10px">
      @csrf
      @method('PUT')

      <label>Full Name</label>
      <input type="text" name="Full_name" value="{{ old('Full_name', $user->Full_name) }}" required>

      <label>ID Number</label>
      <input type="text" name="Id_Number" value="{{ old('Id_Number', $user->Id_Number) }}" required>

      <label>Contact Number</label>
      <input type="text" name="Contact_Number" value="{{ old('Contact_Number', $user->Contact_Number) }}">

      <label>Position</label>
      <input type="text" name="Position" value="{{ old('Position', $user->Position) }}">

      <label>Plate Number</label>
      <input type="text" name="Plate_Number" value="{{ old('Plate_Number', $user->Plate_Number) }}">

      <label>Vehicle Type</label>
      <select name="Vehicle_Type" class="status-select">
        <option value="">–</option>
        <option value="Car" {{ old('Vehicle_Type', $user->Vehicle_Type)==='Car'?'selected':'' }}>Car</option>
        <option value="Motorcycle" {{ old('Vehicle_Type', $user->Vehicle_Type)==='Motorcycle'?'selected':'' }}>Motorcycle</option>
      </select>

      <label>Department</label>
      <input type="text" name="Department" value="{{ old('Department', $user->Department) }}">

      <label>Parking Counts</label>
      <input type="text" name="Parking_counts" value="{{ old('Parking_counts', $user->Parking_counts) }}">

      <div style="display:flex;gap:10px;margin-top:8px">
        <button type="submit" class="add-btn">Update</button>
        <a href="{{ route('userData') }}" class="edit-btn" style="text-decoration:none;display:inline-flex;align-items:center">Cancel</a>
      </div>
    </form>
  </main>
</body>
</html>
