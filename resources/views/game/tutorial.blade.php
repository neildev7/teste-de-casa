<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Last SENAI | Tutorial</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href= "img/logo.png ">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=IM+Fell+English:ital@0;1&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --paper-color: #f7f3e8;
            --wood-color: #5d4037;
            --metal-color: #a9a9a9;
            --gold-color: #ffd700;
            /* MELHORIA: Nova variável para texto claro, essencial para o fundo escuro */
            --text-light: #f0e9d9; 
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'IM Fell English', serif; /* Fonte padrão mais legível e temática */
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
            background: rgba(0, 0, 0, 0.65);
            z-index: 0;
        }
        
        .main-container {
            position: relative;
            z-index: 1;
            padding: clamp(30px, 5vw, 50px) clamp(20px, 4vw, 40px);
            max-width: 700px;
            width: 100%;
            /* MUDANÇA: Fundo de madeira, sem textura de pergaminho */
            background-color: var(--wood-color);
            border: 15px solid;
            border-image: linear-gradient(45deg, var(--metal-color), #8b8b8b) 1;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
            /* MUDANÇA: Cor padrão do texto para claro */
            color: var(--text-light);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s 0.2s forwards ease-out;
        }

        h1 {
            font-family: 'IM Fell English SC', serif;
            font-size: clamp(2rem, 5vw, 2.5rem);
            color: var(--gold-color);
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
            margin-bottom: 25px;
        }

        h3 {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            color: var(--gold-color);
            font-size: 1.3rem;
            margin-top: 30px;
            margin-bottom: 15px;
            border-top: 1px solid rgba(255, 215, 0, 0.3);
            padding-top: 20px;
        }

        /* MELHORIA: Estilo do avatar unificado com as outras páginas */
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
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 15px;
            max-width: 500px; /* Melhora a legibilidade */
            margin-left: auto;
            margin-right: auto;
        }

        p strong {
            color: var(--gold-color);
            font-weight: normal;
        }

        /* MELHORIA: Lista estilizada e sem estilos inline */
        .tutorial-list {
            text-align: left;
            display: inline-block;
            margin-top: 10px;
            padding-left: 20px;
        }
        
        .tutorial-list li {
            margin-bottom: 10px;
            line-height: 1.5;
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
            font-size: 1rem;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Cinzel', serif;
            margin-top: 30px;
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
    <h1>Ramon Moraes te dá as boas-vindas!</h1>
    <img src="{{ asset('img/avatar-4.png') }}" class="tutorial-avatar" alt="Avatar de Ramon Moraes">

    <p>Olá, aventureiro! Eu sou <strong>Ramon Moraes</strong>, seu tutor nesta jornada épica através de <strong>Pixelândia</strong>.</p>
    <p>Prepare seu café ☕, escolha seu Pokémon favorito 🐱‍👤 e vamos aprender tudo que você precisa para se tornar um verdadeiro herói!</p>

    <h3>A Lenda de Pixelândia</h3>
    <p>O mundo de <strong>Pixelândia</strong> viveu em paz por eras, mas agora está em perigo! Monstros misteriosos surgiram das profundezas e cabe a você, com coragem e estratégia, defender o reino e descobrir um segredo que somente o Dragão guarda..</p>

    <h3>Manual do Aventureiro</h3>
    <ul class="tutorial-list">
        <li>Sua jornada começa com a <strong>criação de um personagem</strong>, escolhendo um nome e um avatar.</li>
        <li>Em batalha, cada <strong>ataque</strong> causa dano baseado nos seus atributos contra a defesa do inimigo.</li>
        <li>Ao vencer, você ganhará <strong>experiência</strong> e, ao acumular o suficiente, subirá de <strong>nível</strong> para se tornar mais forte.</li>
    </ul>

    <form action="{{ route('character.allocate', $character->id) }}" method="get">
        <button type="submit" class="btn">Prosseguir para a Batalha</button>
    </form>
</main>

</body>
</html>