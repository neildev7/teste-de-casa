<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Tutorial - Ramon Moraes</title>
  <style>
    body { font-family: monospace; background: #111; color: #0f0; padding: 30px; text-align: center; }
    .avatar { width: 100px; border-radius: 10px; margin-bottom: 20px; }
    .card { display: inline-block; background: #000; border: 1px solid #0f0; padding: 20px; border-radius: 10px; max-width: 500px; }
    button { background: #0f0; color: #111; border: none; padding: 10px 20px; margin-top: 20px; cursor: pointer; font-weight: bold; }
    button:hover { background: #9f9; }
  </style>
</head>
<body>

  <div class="card">
    <h1>👨‍🏫 Ramon Moraes te dá as boas-vindas!</h1>
    <img src="{{ asset('img/avatar-4.png') }}" class="avatar" alt="Ramon Moraes">

    <p>Olá, aventureiro! Eu sou <strong>Ramon Moraes</strong>, seu tutor nesta jornada épica.</p>

    <p>Adoro café ☕ e Pokémon 🐱‍👤, e vou te ensinar tudo para se tornar um verdadeiro herói.</p>

    <h3>Lore do jogo:</h3>
    <p>O mundo de <strong>Pixelândia</strong> está em perigo! Monstros misteriosos surgiram e cabe a você defender o reino.</p>

    <h3>Como jogar:</h3>
    <ul style="text-align: left;">
      <li>Escolha um nome e um avatar para o seu personagem.</li>
      <li>Cada ataque causa dano baseado no seu ataque e força do inimigo.</li>
      <li>O inimigo também pode atacar (em futuras atualizações).</li>
      <li>Ganhe experiência e suba de nível para se tornar mais forte.</li>
    </ul>

    <form action="{{ route('character.allocate', $character->id) }}" method="get">
        <button type="submit">Prosseguir para alocação de pontos</button>
    </form>
  </div>

</body>
</html>
