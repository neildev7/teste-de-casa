<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Last SENAI | O Chamado do Herói</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=IM+Fell+English:ital@0;1&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --paper-color: #f7f3e8;
            --wood-color: #5d4037;
            --metal-color: #a9a9aa;
            --gold-color: #ffd700;
            --text-light: #f0e9d9; 
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'IM Fell English', serif;
            background: var(--paper-color) url("{{ asset('img/giphy.gif') }}") no-repeat center center fixed;
            background-size: cover;
            text-align: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-blend-mode: multiply;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 0;
        }
        
        .main-container {
            position: relative;
            z-index: 1;
            padding: clamp(30px, 5vw, 50px) clamp(20px, 4vw, 40px);
            max-width: 800px; /* Aumentado para caber mais texto */
            width: 100%;
            background-color: var(--wood-color);
            border: 15px solid;
            border-image: linear-gradient(45deg, var(--metal-color), #8b8b8b) 1;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
            color: var(--text-light);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s 0.2s forwards ease-out;
        }

        h1 {
            font-family: 'IM Fell English SC', serif;
            font-size: clamp(2rem, 5vw, 2.8rem); /* Um pouco maior */
            color: var(--gold-color);
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
            margin-bottom: 25px;
        }

        h3 {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            color: var(--gold-color);
            font-size: 1.5rem; /* Mais destaque */
            margin-top: 35px;
            margin-bottom: 15px;
            border-top: 1px solid rgba(255, 215, 0, 0.3);
            padding-top: 25px;
            letter-spacing: 1px;
        }

        .tutorial-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--gold-color);
            padding: 4px;
            background: var(--wood-color);
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
        }

        p {
            font-size: 1.15rem; /* Levemente maior para legibilidade */
            line-height: 1.7;
            margin-bottom: 15px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        p strong, .highlight {
            color: var(--gold-color);
            font-weight: normal;
        }

        .tutorial-list {
            text-align: left;
            display: inline-block;
            margin-top: 10px;
            padding-left: 20px;
            max-width: 550px; /* Garante que listas longas quebrem a linha */
        }
        
        .tutorial-list li {
            font-size: 1.1rem;
            margin-bottom: 12px;
            line-height: 1.6;
            padding-left: 10px;
        }

        .tutorial-list li::marker {
            color: var(--gold-color);
            font-size: 1.2rem;
        }

        .btn {
            display: inline-block;
            background: var(--gold-color);
            color: var(--wood-color);
            border: 3px outset var(--gold-color);
            padding: 15px 35px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Cinzel', serif;
            margin-top: 35px;
        }

        .btn:hover {
            background-color: #ffed4a;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.6);
            transform: translateY(-3px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<main class="main-container">
    <h1>O Pergaminho do Mestre</h1>
    <img src="{{ asset('img/avatar-4.png') }}" class="tutorial-avatar" alt="Avatar de Ramon Moraes">

    <p>Saudações, <strong class="highlight">{{ $character->name }}</strong>! Eu sou <strong>Ramon Moraes</strong>, o guardião desta era e seu guia através dos perigos de <strong>Pixelândia</strong>.</p>
    <p>O destino te escolheu. Sente-se, pois a história que vou contar é a sua.</p>

    <h3>📜 A Lenda de Pixelândia</h3>
    <p>Nosso mundo foi forjado por código e magia, um paraíso de paz. Mas a cada milênio, ocorre o "Grande Reset", um evento que reescreve a realidade. O último reset falhou, deixando para trás um <strong class="highlight">Eco Sombrio</strong>.</p>
    <p>Este eco corrompe a terra e manifesta criaturas de pesadelo: Goblins, Orcs e até mesmo o lendário Dragão que antes nos protegia. Sua missão é purificar a terra e descobrir o segredo por trás da falha do Reset.</p>

    <h3>⚔️ Seus Atributos</h3>
    <p>Seu poder é definido por seus atributos. Na próxima etapa, você distribuirá pontos para moldar seu herói. Entenda o que cada um faz:</p>
    <ul class="tutorial-list">
        <li>❤️ <strong>HP (Pontos de Vida):</strong> Sua vitalidade. Se chegar a zero, a jornada termina.</li>
        <li>✨ <strong>MP (Pontos de Magia):</strong> Energia para usar habilidades especiais.</li>
        <li>⚔️ <strong>Ataque:</strong> Aumenta o dano dos seus ataques físicos.</li>
        <li>🛡️ <strong>Defesa:</strong> Reduz o dano que você recebe de ataques físicos.</li>
        <li>⚡ <strong>Velocidade:</strong> Define quem ataca primeiro em combate (em futuras atualizações).</li>
        <li>🔥 <strong>Ataque Especial:</strong> Aumenta o poder das suas magias e habilidades.</li>
        <li>🔮 <strong>Defesa Especial:</strong> Reduz o dano de ataques mágicos inimigos.</li>
    </ul>

    <h3>📜 Comandos de Batalha</h3>
    <p>Em combate, você terá opções cruciais para sobreviver:</p>
    <ul class="tutorial-list">
        <li><strong>Ataque Físico:</strong> Um golpe básico que não custa MP.</li>
        <li><strong>Feitiços e Habilidades:</strong> Ataques poderosos que consomem MP.</li>
        <li><strong>Poção:</strong> Use uma de suas poções para restaurar HP em um momento de aperto.</li>
    </ul>
    
    <form action="{{ route('character.allocate', $character->id) }}" method="get">
        <button type="submit" class="btn">Entendido, Mestre! Prosseguir!</button>
    </form>
</main>

</body>
</html>