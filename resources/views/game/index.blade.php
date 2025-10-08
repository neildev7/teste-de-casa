<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Criação de Personagem</title>
  <style>
    body { font-family: monospace; background: #111; color: #0f0; text-align: center; padding: 50px; }
    input, select { padding: 10px; margin: 10px; border: none; border-radius: 5px; }
    button { background: #0f0; color: #111; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; }
    button:hover { background: #9f9; }
    .card { background: #000; display: inline-block; padding: 30px; border-radius: 10px; border: 1px solid #0f0; }
    .avatar-preview img { width: 80px; height: 80px; border-radius: 10px; margin: 5px; cursor: pointer; border: 2px solid transparent; }
    .avatar-preview img.selected { border-color: #0f0; }
  </style>
</head>
<body>

  <h1>⚔️ Criação de Personagem ⚔️</h1>
  <div class="card">
    <form method="POST" action="{{ route('character.store') }}">
      @csrf
      <p>Digite o nome do seu herói:</p>
      <input type="text" name="name" placeholder="Ex: Neil, Miguel, Ligabo..." required>

      <p>Escolha um avatar:</p>
      <div class="avatar-preview">
        <img src="/img/avatar-1.png" onclick="selectAvatar(this)" data-value="/img/avatar-1.png">
        <img src="/img/avatar-2.png" onclick="selectAvatar(this)" data-value="/img/avatar-2.png">
        <img src="/img/avatar-3.png" onclick="selectAvatar(this)" data-value="/img/avatar-3.png">
      </div>

      <input type="hidden" name="avatar" id="avatarInput" required>

      <br>
      <button type="submit">Começar Aventura 🚀</button>
    </form>
  </div>

  <script>
    function selectAvatar(img) {
      document.querySelectorAll('.avatar-preview img').forEach(i => i.classList.remove('selected'));
      img.classList.add('selected');
      document.getElementById('avatarInput').value = img.dataset.value;
    }
  </script>

</body>
</html>
