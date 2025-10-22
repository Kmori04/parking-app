<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>VIP Parking</title>
  @vite(['resources/css/availability_vip.css','resources/js/app.js'])
</head>
<body>
  <header>
    <a href="{{ route('availability') }}" class="back-btn" title="Go Back">⬅ Back</a>
  </header>

  <main>
    <h1>VIP PARKING SLOTS</h1>

    @php
      // Natural sort like A1, A2, A10…
      $sorted = $VipParkingStatusData->sortBy(function($r){
        return [ substr($r->Slot_number,0,1), (int)substr($r->Slot_number,1) ];
      })->values();

      [$available, $notAvailable] = $sorted->partition(
        fn($row) => (int)$row->Availability_Table_Available_id === 1
      );

      // lookup for map pins
      $byNumber = $sorted->keyBy('Slot_number');

      // Only rotate groups listed here (none for now)
      $rotatedGroups = [];

      // === SAME COORDINATES AS MOTORS ===
      $slotCoords = [
        'C1'=>[185,120],'C2'=>[225,120],'C3'=>[265,120],'C4'=>[305,120],
        'C5'=>[345,120],'C6'=>[385,120],'C7'=>[425,120],

        'F1'=>[580,110],'F2'=>[610,110],'F3'=>[640,110],
        'F4'=>[670,110],'F5'=>[700,110],'F6'=>[730,110],

        'F16'=>[575,150],'F17'=>[575,175],'F18'=>[575,200],
        'F13'=>[620,150],'F14'=>[620,175],'F15'=>[620,200],
        'F10'=>[665,150],'F11'=>[665,175],'F12'=>[665,200],
        'F7' =>[710,150],'F8' =>[710,175],'F9' =>[710,200],

        'D1'=>[145,180],'D2'=>[145,225],'D3'=>[145,270],
        'D4'=>[145,315],'D5'=>[145,360],'D6'=>[145,405],

        'B5'=>[355,240],'B6'=>[355,285],'B7'=>[355,330],'B8'=>[355,375],
        'B1'=>[420,240],'B2'=>[420,285],'B3'=>[420,330],'B4'=>[420,375],

        'A5'=>[618,240],'A6'=>[618,285],'A7'=>[618,330],'A8'=>[618,375],
        'A1'=>[685,240],'A2'=>[685,285],'A3'=>[685,330],'A4'=>[685,375],

        'E1'=>[240,445],'E2'=>[280,445],

      
        'G1'=>[380,435],'G2'=>[415,435],'G3'=>[450,435],
        'G4'=>[485,435],'G5'=>[520,435],'G6'=>[555,435],'G7'=>[590,435],
      ];
    @endphp

    <div class="layout">

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
                    <form method="POST" action="{{ route('vips.update', $s->Slot_id) }}" class="status-form">
                      @csrf
                      @method('PATCH')
                      <select name="status" class="status-select">
                        <option value="1" {{ (int)$s->Availability_Table_Available_id===1 ? 'selected':'' }}>Available</option>
                        <option value="2" {{ (int)$s->Availability_Table_Available_id===2 ? 'selected':'' }}>Not Available</option>
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
                    <form method="POST" action="{{ route('vips.update', $s->Slot_id) }}" class="status-form">
                      @csrf
                      @method('PATCH')
                      <select name="status" class="status-select">
                        <option value="1" {{ (int)$s->Availability_Table_Available_id===1 ? 'selected':'' }}>Available</option>
                        <option value="2" {{ (int)$s->Availability_Table_Available_id===2 ? 'selected':'' }}>Not Available</option>
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
              $rec   = $byNumber->get($slot);
              $taken = $rec && (int)$rec->Availability_Table_Available_id === 2;

              $group = substr($slot, 0, 1);      // A..G
              $isInteractive = ($group === 'G');

              $num = (int)substr($slot, 1);
              $isRotated = in_array($group, $rotatedGroups, true);
              $isFmini   = ($group === 'F' && $num >= 1 && $num <= 6);
            @endphp

            <button
              type="button"
              class="map-slot group-{{ $group }}
                     {{ $isInteractive ? ($taken ? 'taken' : 'available') : 'disabled-slot' }}
                     {{ $isRotated ? 'rotated' : '' }}
                     {{ $isFmini ? 'f-pillar' : '' }}"
              style="left: {{ $x }}px; top: {{ $y }}px;"
              data-slot-id="{{ $rec->Slot_id ?? '' }}"
              data-slot-number="{{ $slot }}"
              data-status="{{ $taken ? 2 : 1 }}"
            >
              <span>{{ $slot }}</span>
            </button>
          @endforeach
        </div>

        <p class="map-legend">
          <span class="legend-dot avail"></span>Available &nbsp;
          <span class="legend-dot taken"></span>Not Available &nbsp;
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
        const res = await fetch(`{{ url('/vips') }}/${slotId}/status`,{
          method:'PATCH',
          headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
          body:JSON.stringify({status})
        });
        if(!res.ok) throw new Error('update failed');
        return true;
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

      function paintPin(slotId, status){
        const pin = document.querySelector(`.map-slot[data-slot-id="${slotId}"]`);
        if(!pin) return;
        pin.dataset.status = String(status);
        pin.classList.toggle('taken', Number(status)===2);
        pin.classList.toggle('available', Number(status)===1);
      }

     
      document.querySelectorAll('.map-slot.available, .map-slot.taken').forEach(pin=>{
        pin.addEventListener('click', async (ev)=>{
          ev.preventDefault();
          ev.stopPropagation();
          const id = pin.dataset.slotId;
          if(!id) return;
          const next = Number(pin.dataset.status)===1 ? 2 : 1;
          try{
            await updateStatus(id,next);
            paintPin(id,next);
            moveRow(id,next);
          }catch(e){ alert('Failed to update'); }
        });
      });

      document.querySelectorAll('form.status-form').forEach(f=>{
        f.addEventListener('submit', async (ev)=>{
          ev.preventDefault();
          const row = f.closest('tr');
          const id  = row.dataset.slotId;
          const val = Number(f.querySelector('select[name="status"]').value);
          try{
            await updateStatus(id,val);
            moveRow(id,val);
            paintPin(id, val);
          }catch(e){ alert('Failed to save'); }
        });
      });

      sortBoth();
    })();
  </script>
</body>
</html>
