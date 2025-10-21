<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cars</title>
  @vite(['resources/css/availability_cars.css', 'resources/js/app.js'])
</head>
<body>
  <header>
    <a href="{{ route('availability') }}" class="back-btn" title="Go Back">⬅ Back</a>
  </header>

  <main>
    <h1>CAR PARKING SLOTS</h1>

    @php
      // Sort like A1, A2, A10…
      $sorted = $CarParkingStatusData->sortBy(function($r){
        return [ substr($r->Slot_number,0,1), (int)substr($r->Slot_number,1) ];
      })->values();

      [$available, $notAvailable] = $sorted->partition(
        fn($row) => (int)$row->Availability_Table_Available_id === 1
      );

      $byNumber = $sorted->keyBy('Slot_number');

      $slotCoords = [
        'C1'=>[240,145],'C2'=>[280,145],'C3'=>[320,145],'C4'=>[360,145],
        'C5'=>[400,145],'C6'=>[440,145],'C7'=>[480,145],

        'G1'=>[505,145],'G2'=>[505,176],'G3'=>[532,145],'G4'=>[532,176],

        'F1'=>[590,145],'F2'=>[616,145],'F3'=>[642,145],'F4'=>[668,145],'F5'=>[694,145],
        'F6'=>[590,176],'F7'=>[616,176],'F8'=>[642,176],'F9'=>[668,176],'F10'=>[694,176],

        'H1'=>[150,145],'H2'=>[150,165],'H3'=>[150,185],

        'D1'=>[135,235],'D2'=>[135,270],'D3'=>[135,305],
        'D4'=>[135,340],'D5'=>[135,375],'D6'=>[135,410],

        // B center twin columns (with base lines gap)
        'B5'=>[355,240],'B6'=>[355,275],'B7'=>[355,310],'B8'=>[355,345],
        'B1'=>[395,240],'B2'=>[395,275],'B3'=>[395,310],'B4'=>[395,345],

        'A5'=>[615,235],'A6'=>[615,270],'A7'=>[615,305],'A8'=>[615,340],
        'A1'=>[655,235],'A2'=>[655,270],'A3'=>[655,305],'A4'=>[655,340],

        'E1'=>[225,455],'E2'=>[260,455],

        'I1'=>[380,455],'I2'=>[415,455],'I3'=>[450,455],'I4'=>[485,455],'I5'=>[520,455],
      ];

      $groupOffset = [
        'A' => [0, 0],
        'B' => [0, 0],
        'C' => [-55, -22],
        'D' => [0, 0],
        'E' => [0, 0],
        'F' => [0, 0],
        'G' => [0, 0],
        'H' => [0, 0],
        'I' => [0, 0],
      ];
    @endphp

    <div class="layout">
      <!-- LEFT: two tables -->
      <div class="left-side">
        <div class="slots-grid">
          <section class="panel">
            <h2 class="panel-title">Available</h2>
            <table class="slots" id="tbl-available">
              <thead><tr><th>Slot Number</th><th>Status</th></tr></thead>
              <tbody>
              @foreach($available as $s)
                <tr data-slot-id="{{ $s->Slot_id }}" data-slot-number="{{ $s->Slot_number }}">
                  <td>{{ $s->Slot_number }}</td>
                  <td class="action-cell">
                    <form method="POST" action="{{ route('availability.cars.update', $s->Slot_id) }}" class="status-form">
                      @csrf
                      <select name="status" class="status-select">
                        <option value="1" selected>Available</option>
                        <option value="2">Not Available</option>
                      </select>
                      <button type="submit" class="edit-btn">Save</button>
                    </form>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </section>

          <section class="panel">
            <h2 class="panel-title">Not Available</h2>
            <table class="slots" id="tbl-notavailable">
              <thead><tr><th>Slot Number</th><th>Status</th></tr></thead>
              <tbody>
              @foreach($notAvailable as $s)
                <tr data-slot-id="{{ $s->Slot_id }}" data-slot-number="{{ $s->Slot_number }}">
                  <td>{{ $s->Slot_number }}</td>
                  <td class="action-cell">
                    <form method="POST" action="{{ route('availability.cars.update', $s->Slot_id) }}" class="status-form">
                      @csrf
                      <select name="status" class="status-select">
                        <option value="1">Available</option>
                        <option value="2" selected>Not Available</option>
                      </select>
                      <button type="submit" class="edit-btn">Save</button>
                    </form>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </section>
        </div>
      </div>

      <!-- RIGHT: PURE HTML/CSS MAP (bigger + centered) -->
      <aside class="map-side">
        <h2 class="panel-title">Map</h2>

        <div class="campus">
          <div class="bar canteen">CANTEEN</div>
          <div class="bar cbuilding">C BUILDING</div>
          <div class="arrow">↓</div>
          <div class="pillar stage"><span>S</span><span>T</span><span>A</span><span>G</span><span>E</span></div>
          <div class="pillar ccs"><span>C</span><span>C</span><span>S</span><span>&nbsp;</span><span>B</span><span>U</span><span>I</span><span>L</span><span>D</span><span>I</span><span>N</span><span>G</span></div>
          <div class="bar faculty">CASED FACULTY</div>
          <div class="bar study">STUDY CENTER</div>

          @foreach($slotCoords as $slot => [$x,$y])
            @php
              $rec = $byNumber->get($slot);
              if (!$rec) continue;
              $taken = (int)$rec->Availability_Table_Available_id === 2;

              // Get group letter and its offset
              $group = substr($slot, 0, 1);
              [$dx, $dy] = $groupOffset[$group] ?? [0,0];

              $left = $x + $dx;
              $top  = $y + $dy;
            @endphp

            <button
              class="map-slot group-{{ $group }} {{ $taken ? 'taken':'available' }}"
              style="left: {{ $left }}px; top: {{ $top }}px;"
              data-slot-id="{{ $rec->Slot_id }}"
              data-slot-number="{{ $slot }}"
              data-status="{{ $taken ? 2 : 1 }}"
              data-group="{{ $group }}"
            >{{ $slot }}</button>
          @endforeach

          <!-- ADDED STATIC F1–F6 BOXES -->
          <div class="moto-box" style="left:580px;">F1</div>
          <div class="moto-box" style="left:610px;">F2</div>
          <div class="moto-box" style="left:640px;">F3</div>
          <div class="moto-box" style="left:670px;">F4</div>
          <div class="moto-box" style="left:700px;">F5</div>
          <div class="moto-box" style="left:730px;">F6</div>
        </div>

       <div class="f7to16-grid">
  <div class="fbox"><span>F7</span></div>
  <div class="fbox"><span>F8</span></div>
  <div class="fbox"><span>F9</span></div>
  <div class="fbox"><span>F10</span></div>
  <div class="fbox"><span>F11</span></div>
  <div class="fbox"><span>F12</span></div>
  <div class="fbox"><span>F13</span></div>
  <div class="fbox"><span>F14</span></div>
  <div class="fbox"><span>F15</span></div>
  <div class="fbox"><span>F16</span></div>
  <div class="fbox"><span>F17</span></div>
  <div class="fbox"><span>F18</span></div>
</div>

<div class="i1to7-grid">
  <div class="ibox"><span>I1</span></div>
  <div class="ibox"><span>I2</span></div>
  <div class="ibox"><span>I3</span></div>
  <div class="ibox"><span>I4</span></div>
  <div class="ibox"><span>I5</span></div>
  <div class="ibox"><span>I6</span></div>
  <div class="ibox"><span>I7</span></div>
</div>


      
        <p class="map-legend">
          <span class="legend-dot avail"></span>Available &nbsp;
          <span class="legend-dot taken"></span>Taken &nbsp;
          <span class="legend-dot restricted"></span>Others
        </p>
      </aside>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
  </main>

  <script>
  (function(){
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    function sortBody(tbody){
      const rows = Array.from(tbody.querySelectorAll('tr'));
      rows.sort((a,b)=>{
        const A = a.dataset.slotNumber, B = b.dataset.slotNumber;
        const aL = A[0], bL = B[0];
        const aN = parseInt(A.slice(1),10), bN = parseInt(B.slice(1),10);
        if(aL!==bL) return aL.localeCompare(bL);
        return aN-bN;
      });
      rows.forEach(r=>tbody.appendChild(r));
    }
    function sortBoth(){
      sortBody(document.querySelector('#tbl-available tbody'));
      sortBody(document.querySelector('#tbl-notavailable tbody'));
    }

    async function updateStatus(slotId, status){
      const res = await fetch(`{{ url('/availability/cars') }}/${slotId}/status`,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
        body:JSON.stringify({status})
      });
      if(!res.ok) throw new Error('update failed');
      return true;
    }

    function paintPin(slotId, status){
      const pin = document.querySelector(`.map-slot[data-slot-id="${slotId}"]`);
      if(!pin) return;
      pin.dataset.status = String(status);
      pin.classList.toggle('taken', Number(status)===2);
      pin.classList.toggle('available', Number(status)===1);
    }

    function moveRow(slotId, status){
      const row = document.querySelector(`tr[data-slot-id="${slotId}"]`);
      if(!row) return;
      const sel = row.querySelector('select[name="status"]');
      if(sel) sel.value = String(status);

      const target = (Number(status)===1)
        ? document.querySelector('#tbl-available tbody')
        : document.querySelector('#tbl-notavailable tbody');

      if(row.parentElement !== target){
        row.parentElement.removeChild(row);
        target.appendChild(row);
      }
      sortBoth();
    }

    // click a pin to toggle available/taken
    document.querySelectorAll('.map-slot').forEach(pin=>{
      pin.addEventListener('click', async ()=>{
        const id = pin.dataset.slotId;
        const next = Number(pin.dataset.status)===1 ? 2 : 1;
        try{
          await updateStatus(id,next);
          paintPin(id,next);
          moveRow(id,next);
        }catch(e){ alert('Failed to update'); }
      });
    });

    // submit from tables
    document.querySelectorAll('form.status-form').forEach(f=>{
      f.addEventListener('submit', async (ev)=>{
        ev.preventDefault();
        const row = f.closest('tr');
        const id  = row.dataset.slotId;
        const val = Number(f.querySelector('select[name="status"]').value);
        try{
          await updateStatus(id,val);
          paintPin(id,val);
          moveRow(id,val);
        }catch(e){ alert('Failed to save'); }
      });
    });

    sortBoth();
  })();
  </script>
</body>
</html>
