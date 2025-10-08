<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Last SENAI | Forje seu Herói</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href= "img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=IM+Fell+English:ital@0;1&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --paper-color: #f7f3e8;
            --wood-color: #5d4037;
            --metal-color: #a9a9a9;
            --gold-color: #ffd700;
            --danger-color: #a02c2c;
            --text-dark: #000000;
            --ink-color: #4a382d;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cinzel', serif;
            background: var(--paper-color) url("{{ asset('img/giphy.gif') }}") no-repeat center center fixed;
            background-size: cover;
            color: var(--text-dark);
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
            background-color: var(--wood-color);
            background-image: var(--bg-texture);
            background-repeat: repeat;
            background-size: 200px;
            border: 15px solid;
            border-image: linear-gradient(45deg, var(--metal-color), #8b8b8b) 1;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
            /* MELHORIA: Animação de entrada */
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s 0.2s forwards ease-out;
        }

        h1 {
            font-family: 'IM Fell English SC', serif;
            font-size: clamp(2rem, 5vw, 2.8rem);
            color: var(--gold-color);
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
            margin-bottom: 40px;
            border-bottom: 3px double var(--gold-color);
            padding-bottom: 10px;
            display: inline-block;
        }

        /* MELHORIA: Acessibilidade com fieldset e legend */
        .form-fieldset {
            border: none;
            margin-bottom: 25px;
        }

        .form-legend {
    font-family: 'IM Fell English', serif;
    font-size: 1.4rem;
    font-style: italic;
    color: #FFFFFF; /* <<< TEXTO BRANCO APLICADO AQUI */
    /* Sombra escura para garantir a leitura no fundo claro */
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8); 
    margin-bottom: 15px;
    padding: 0 10px;
}

        .form-input {
            width: 100%;
            max-width: 400px;
            padding: 12px 20px;
            background: var(--paper-color);
            border: 2px solid var(--wood-color);
            border-radius: 2px;
            font-family: 'Cinzel', serif;
            font-size: 1rem;
            color: var(--text-dark);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        /* MELHORIA: Feedback visual para campos inválidos */
        .form-input:required:invalid {
            border-color: var(--danger-color);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2), 0 0 8px rgba(160, 44, 44, 0.5);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--gold-color);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.2), 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .avatar-selection {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .avatar-selection img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            cursor: pointer;
            border: 4px solid var(--wood-color);
            padding: 3px;
            background: var(--wood-color);
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
        }

        .avatar-selection img.selected {
            transform: scale(1.1);
            border-color: var(--gold-color);
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.7);
        }
        
        /* Botão principal com mais destaque */
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
            margin-top: 20px;
        }

        .btn:hover:not(:disabled) {
            background-color: #ffed4a;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.6);
            transform: translateY(-3px);
        }

        /* MELHORIA: Estilo claro para o botão desabilitado (UX) */
        .btn:disabled {
            background: var(--metal-color);
            border-color: var(--metal-color);
            color: #888;
            opacity: 0.7;
            cursor: not-allowed;
            text-shadow: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        /* MELHORIA: Animação de erro para o formulário */
        .form-error-shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
            20%, 40%, 60%, 80% { transform: translateX(8px); }
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<main class="main-container">
    <h1>⚔️ Forje seu Herói ⚔️</h1>
    
    <form id="characterForm" method="POST" action="{{ route('character.store') }}" novalidate>
        @csrf
        
        <fieldset class="form-fieldset">
            <legend class="form-legend">Digite o nome do seu campeão:</legend>
            <input type="text" id="nameInput" name="name" class="form-input" placeholder="O nome que ecoará nas lendas..." required minlength="3">
        </fieldset>

        <fieldset class="form-fieldset" id="avatarFieldset">
            <legend class="form-legend">Escolha sua face:</legend>
            <div class="avatar-selection">
                <img src="{{ asset('img/avatar-1.png') }}" alt="Opção de avatar 1" data-value="/img/avatar-1.png">
                <img src="{{ asset('img/avatar-2.png') }}" alt="Opção de avatar 2" data-value="/img/avatar-2.png">
                <img src="{{ asset('img/avatar-3.png') }}" alt="Opção de avatar 3" data-value="/img/avatar-3.png">
            </div>
            <input type="hidden" name="avatar" id="avatarInput" required>
        </fieldset>

        <button type="submit" id="submitBtn" class="btn" disabled>Inscreva seu Nome no Livro</button>
    </form>
</main>

<script>
    // MELHORIA: Script executado após o DOM carregar e com validação em tempo real
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('characterForm');
        const nameInput = document.getElementById('nameInput');
        const avatarInput = document.getElementById('avatarInput');
        const avatarFieldset = document.getElementById('avatarFieldset');
        const avatarSelection = document.querySelector('.avatar-selection');
        const allAvatars = avatarSelection.querySelectorAll('img');
        const submitBtn = document.getElementById('submitBtn');

        // Função central para validar o formulário e habilitar/desabilitar o botão
        const validateForm = () => {
            const isNameValid = nameInput.value.trim().length >= 3;
            const isAvatarSelected = avatarInput.value !== '';
            submitBtn.disabled = !(isNameValid && isAvatarSelected);
        };

        // Adiciona validação ao digitar o nome
        nameInput.addEventListener('input', validateForm);

        // Adiciona validação ao selecionar um avatar
        avatarSelection.addEventListener('click', (event) => {
            const clickedAvatar = event.target.closest('img');
            if (!clickedAvatar) return;

            allAvatars.forEach(img => img.classList.remove('selected'));
            clickedAvatar.classList.add('selected');
            avatarInput.value = clickedAvatar.dataset.value;
            
            // Remove o destaque de erro se houver
            avatarFieldset.classList.remove('form-error-shake');
            
            validateForm();
        });

        // Validação final ao tentar enviar o formulário
        form.addEventListener('submit', (event) => {
            event.preventDefault(); // Previne o envio padrão para validarmos primeiro

            if (submitBtn.disabled) {
                // Se o botão estiver desabilitado, algo está errado
                if (avatarInput.value === '') {
                    avatarFieldset.classList.add('form-error-shake');
                    // Remove a animação após terminar para que possa ser reativada
                    setTimeout(() => avatarFieldset.classList.remove('form-error-shake'), 500);
                }
            } else {
                // Se tudo estiver OK, envia o formulário
                form.submit();
            }
        });
        
        // Validação inicial caso a página recarregue com campos preenchidos
        validateForm();
    });
</script>

</body>
</html>