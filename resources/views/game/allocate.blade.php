<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Alocação de Pontos - {{ $character->name }}</title>
  <style>
    body { font-family: monospace; background: #111; color: #0f0; text-align: center; padding: 50px; }
    .container { max-width: 500px; margin: auto; background: #000; padding: 20px; border-radius: 10px; border: 1px solid #0f0; }
    h1 { margin-bottom: 20px; }
    input[type=number] { width: 60px; padding: 5px; margin: 5px; text-align: center; }
    button { background: #0f0; color: #111; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 20px; }
    button:hover { background: #9f9; }
    .attribute { display: flex; justify-content: space-between; align-items: center; margin: 10px 0; padding: 5px; border-bottom: 1px solid #0f0; }
    .points-left { font-weight: bold; margin-bottom: 20px; }
  </style>
</head>
<body>

  <div class="container">
    <h1>💪 Alocação de Pontos - {{ $character->name }}</h1>
    <p class="points-left">Total de pontos restantes: <span id="pointsLeft">50</span></p>

    <form method="POST" action="{{ route('character.allocate.store', $character->id) }}">
      @csrf

      @php
        $attributes = ['hp','mp','attack','defense','speed','special_attack','special_defense'];
        $maxPerAttribute = 20; // Limite por atributo para equilibrar
        $totalPoints = 50; // Total de pontos que o jogador pode distribuir
      @endphp

      @foreach($attributes as $attr)
        <div class="attribute">
          <label for="{{ $attr }}">{{ ucfirst(str_replace('_',' ',$attr)) }}:</label>
          <input type="number" id="{{ $attr }}" name="{{ $attr }}" value="0" min="0" max="{{ $maxPerAttribute }}">
        </div>
      @endforeach

      <button type="submit">Confirmar Atributos</button>
    </form>
  </div>

  <script>
    const totalPoints = {{ $totalPoints }};
    const maxPerAttr = {{ $maxPerAttribute }};
    const pointsLeftEl = document.getElementById('pointsLeft');
    const inputs = document.querySelectorAll('input[type=number]');

    function updatePoints() {
      let used = 0;
      inputs.forEach(i => used += parseInt(i.value));
      pointsLeftEl.innerText = Math.max(0, totalPoints - used);
    }

    inputs.forEach(i => {
      i.addEventListener('input', () => {
        let value = parseInt(i.value);
        if (value > maxPerAttr) i.value = maxPerAttr;

        let used = 0;
        inputs.forEach(inp => used += parseInt(inp.value));
        if (used > totalPoints) i.value = value - (used - totalPoints);
        updatePoints();
      });
    });

    updatePoints();
  </script>

</body>
</html>
