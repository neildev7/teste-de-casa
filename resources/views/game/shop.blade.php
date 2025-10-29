<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>A Caravana Mágica</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #1a1c2c;
            --ui-main: #5a3a2b;
            --ui-border-light: #a18c7c;
            --ui-border-dark: #3f2a1f;
            --text-light: #ffffff;
            --text-highlight: #ffc800;
            --success-color: #7cb342;
            --error-color: #e53935;
            --rarity-common: #ffffff; --rarity-uncommon: #1eff00; --rarity-rare: #0070dd;
            --cursor-pointer: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 8 8"><path fill="%23ffc800" d="M0 0v8l4-4-4-4z"/></svg>') 8 8, auto;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Press Start 2P', cursive;
            background: url("{{ asset('img/shop_bg.gif') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex; justify-content: center; align-items: center;
            padding: 20px;
            background-color: var(--bg-dark);
            background-blend-mode: multiply;
            color: var(--text-light);
            image-rendering: pixelated;
        }

        .shop-container {
            position: relative; z-index: 1;
            padding: clamp(20px, 4vw, 30px);
            max-width: 1200px; width: 100%;
            background: var(--ui-main);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light), 0 10px 30px rgba(0,0,0,0.5);
            opacity: 0;
            animation: fadeIn 0.5s 0.2s forwards;
        }

        .shop-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 4px solid var(--ui-border-dark);
        }

        h1 { font-size: clamp(1.8rem, 5vw, 2.5rem); color: var(--text-highlight); text-shadow: 3px 3px #000; }
        .shop-header p { font-size: 0.9rem; max-width: 600px; margin: 15px auto 0 auto; line-height: 1.6; }
        
        .shop-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 992px) {
            .shop-layout { grid-template-columns: 300px 1fr; }
        }

        .panel {
            background: var(--bg-dark);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light);
            padding: 20px;
        }
        
        .panel h3 { font-size: 1.2rem; color: var(--text-highlight); margin: 0 0 15px 0; text-shadow: 2px 2px #000; }
        .vendor-avatar { width: 100px; height: 100px; border: 4px solid var(--ui-border-light); margin: 0 auto 15px; display: block; }
        .gold-display { font-size: 1.2rem; margin-bottom: 15px; }
        .gold-display span { font-weight: bold; color: var(--text-highlight); }

        .stats-list { list-style: none; padding: 0; text-align: left; font-size: 0.9rem; line-height: 1.8; }
        .stats-list li { display: flex; justify-content: space-between; }
        .stats-list span { color: var(--text-highlight); }
        
        .item-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; }
        .item-card {
            background: var(--ui-main);
            border: 2px solid var(--ui-border-light);
            padding: 15px; text-align: center;
            display: flex; flex-direction: column;
            transition: all 0.2s;
        }
        .item-card:hover { background: var(--ui-border-dark); }
        
        /* Melhoria: Estrutura HTML/CSS mais limpa para o nome e raridade */
        .item-name { font-size: 0.9rem; margin-bottom: 8px; }
        .rarity-tag { 
            font-size: 0.7rem; padding: 2px 4px; 
            display: inline-block; margin-bottom: 12px; /* Aumentei a margem */
            border: 1px solid; 
        }
        .rarity-common { color: var(--rarity-common); border-color: var(--rarity-common); }
        .rarity-uncommon { color: var(--rarity-uncommon); border-color: var(--rarity-uncommon); }
        .rarity-rare { color: var(--rarity-rare); border-color: var(--rarity-rare); }

        .item-card p { font-size: 0.8rem; line-height: 1.4; margin-bottom: 10px; flex-grow: 1; color: var(--ui-border-light); }
        .item-cost { font-weight: 700; color: var(--text-highlight); }

        .buy-btn {
            background: var(--ui-border-light); color: var(--bg-dark);
            border: 2px solid var(--ui-border-dark); padding: 8px;
            font-size: 0.9rem; transition: all 0.1s; cursor: var(--cursor-pointer);
            font-family: 'Press Start 2P', cursive;
            margin-top: auto;
            outline: none;
        }
        .buy-btn:hover:not(:disabled) { background: var(--text-highlight); }
        /* Melhoria A11y: Estado de foco */
        .buy-btn:focus-visible {
            background: var(--text-highlight);
            box-shadow: 0 0 0 2px var(--bg-dark), 0 0 0 4px var(--text-highlight);
        }
        .buy-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        
        .message-area {
            margin-top: 20px; padding: 15px;
            background: var(--bg-dark);
            border: 2px solid var(--ui-border-light);
            min-height: 2rem;
            text-align: center; font-size: 0.9rem;
            opacity: 0; transition: opacity 0.5s;
        }
        .message-area.visible { opacity: 1; }

        .btn {
            background: var(--ui-main); color: var(--text-light);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light);
            padding: 15px 35px; text-decoration: none;
            font-size: 1.2rem; transition: all 0.1s;
            cursor: var(--cursor-pointer);
            font-family: 'Press Start 2P', cursive;
            text-transform: uppercase;
            outline: none;
        }
        .btn:hover:not(:disabled) { background: var(--ui-border-light); color: var(--bg-dark); }
        /* Melhoria A11y: Estado de foco */
        .btn:focus-visible {
            background: var(--ui-border-light); color: var(--bg-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light), 0 0 0 4px var(--text-highlight);
        }

        .btn-proceed {
            margin-top: 30px; display: block;
            width: 100%;
        }
        
        @keyframes fadeIn { to { opacity: 1; } }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <main class="shop-container">
        <header class="shop-header">
            <h1>CARAVANA MÁGICA</h1>
            <p>"SOBREVIVEU, HERÓI? MEUS ITENS PODEM SER A DIFERENÇA ENTRE A GLÓRIA E O ESQUECIMENTO. ESCOLHA COM SABEDORIA."</p>
        </header>

        <div class="shop-layout">
            <aside class="character-panel panel">
                <img src="{{ asset($character->avatar) }}" alt="Avatar do Herói" class="vendor-avatar">
                <h3>{{ $character->name }}</h3>
                <div class="gold-display">OURO: 
                    <span id="playerGold" aria-live="polite">{{ $character->gold }}</span>
                </div>
                <ul class="stats-list">
                    <li>HP: <span id="stat-hp" aria-live="polite">{{ $character->hp }}</span></li>
                    <li>MP: <span id="stat-mp" aria-live="polite">{{ $character->mp }}</span></li>
                    <li>ATQ: <span id="stat-attack" aria-live="polite">{{ $character->attack }}</span></li>
                    <li>DEF: <span id="stat-defense" aria-live="polite">{{ $character->defense }}</span></li>
                    <li>POÇÕES: <span id="stat-potions" aria-live="polite">{{ $character->potions }}</span></li>
                </ul>
            </aside>

            <section class="items-panel panel">
                <h3>MERCADORIAS</h3>
                <div class="item-grid" id="itemGrid">
                    
                    <div class="item-card" data-cost="50">
                        <span class="rarity-tag rarity-common">COMUM</span>
                        <h4 class="item-name">POÇÃO DE VIDA</h4>
                        <p>+1 POÇÃO</p>
                        <p class="item-cost">50 OURO</p>
                        <button class="buy-btn" data-item-id="potion" 
                                aria-label="Comprar Poção de Vida (50 Ouro)">
                            COMPRAR
                        </button>
                    </div>

                    <div class="item-card" data-cost="75">
                        <span class="rarity-tag rarity-uncommon">INCOMUM</span>
                        <h4 class="item-name">ELIXIR DE MANA</h4>
                        <p>+50 MP (Recupera)</p>
                        <p class="item-cost">75 OURO</p>
                        <button class="buy-btn" data-item-id="elixir" 
                                aria-label="Comprar Elixir de Mana (75 Ouro)">
                            COMPRAR
                        </button>
                    </div>

                    <div class="item-card" data-cost="150">
                        <span class="rarity-tag rarity-common">COMUM</span>
                        <h4 class="item-name">ESPADA DE FERRO</h4>
                        <p>+5 ATAQUE</p>
                        <p class="item-cost">150 OURO</p>
                        <button class="buy-btn" data-item-id="sword1"
                                aria-label="Comprar Espada de Ferro (150 Ouro)">
                            COMPRAR
                        </button>
                    </div>

                    <div class="item-card" data-cost="120">
                        <span class="rarity-tag rarity-common">COMUM</span>
                        <h4 class="item-name">ESCUDO DE MADEIRA</h4>
                        <p>+5 DEFESA</p>
                        <p class="item-cost">120 OURO</p>
                        <button class="buy-btn" data-item-id="shield1"
                                aria-label="Comprar Escudo de Madeira (120 Ouro)">
                            COMPRAR
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <div class="message-area" id="messageArea" role="alert" aria-live="assertive"></div>
        
        <a href="{{ route('character.' . $next_stage, $character->id) }}" class="btn btn-proceed">PROSSEGUIR</a>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Melhoria: Encapsulando tudo em um objeto 'app'
        const app = {
            state: {
                playerGold: 0,
            },
            ui: {
                buyButtons: document.querySelectorAll('.buy-btn'),
                goldDisplay: document.getElementById('playerGold'),
                messageArea: document.getElementById('messageArea'),
                itemCards: document.querySelectorAll('.item-card'),
                stats: {
                    hp: document.getElementById('stat-hp'),
                    mp: document.getElementById('stat-mp'),
                    attack: document.getElementById('stat-attack'),
                    defense: document.getElementById('stat-defense'),
                    potions: document.getElementById('stat-potions'),
                }
            },
            urls: {
                buy: "{{ route('character.shop.buy', $character->id) }}"
            },
            
            init() {
                // Melhoria Robustez: Armazena o ouro inicial no estado do JS
                this.state.playerGold = parseInt(this.ui.goldDisplay.textContent) || 0;
                this.bindEvents();
                this.updateButtonStates(); // Desabilita botões que o jogador não pode pagar
            },

            bindEvents() {
                this.ui.buyButtons.forEach(button => {
                    button.addEventListener('click', (e) => this.handleBuyClick(e));
                });
            },
            
            /**
             * Melhoria Robustez: Verifica o ouro do jogador e desabilita/habilita botões.
             */
            updateButtonStates() {
                this.ui.itemCards.forEach(card => {
                    const cost = parseInt(card.dataset.cost);
                    const button = card.querySelector('.buy-btn');
                    if (cost > this.state.playerGold) {
                        button.disabled = true;
                    }
                });
            },

            /**
             * Atualiza o painel de stats do jogador
             */
            updateStatsUI(newStats) {
                if (!newStats) return;
                this.ui.stats.hp.textContent = newStats.hp;
                this.ui.stats.mp.textContent = newStats.mp;
                this.ui.stats.attack.textContent = newStats.attack;
                this.ui.stats.defense.textContent = newStats.defense;
                this.ui.stats.potions.textContent = newStats.potions;
            },
            
            /**
             * Mostra a mensagem de sucesso ou erro
             */
            showMessage(text, isSuccess) {
                this.ui.messageArea.classList.add('visible');
                this.ui.messageArea.textContent = text;
                this.ui.messageArea.style.color = isSuccess ? 'var(--success-color)' : 'var(--error-color)';
                
                // Esconde a mensagem depois de 3 segundos
                setTimeout(() => {
                    this.ui.messageArea.classList.remove('visible');
                }, 3000);
            },
            
            /**
             * Função unificada para enviar requisições
             */
            async sendRequest(url, options) {
                try {
                    const response = await fetch(url, options);
                    if (!response.ok) throw new Error('Falha no servidor.');
                    
                    const data = await response.json();
                    if (!data.success) throw new Error(data.message || 'Erro desconhecido.');
                    
                    return data;
                } catch (error) {
                    console.error("Erro na requisição:", error);
                    throw error;
                }
            },

            /**
             * Lida com o clique no botão de comprar
             */
            async handleBuyClick(event) {
                const button = event.target;
                const itemId = button.dataset.itemId;
                
                button.disabled = true;
                button.textContent = '...';

                try {
                    const data = await this.sendRequest(this.urls.buy, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ itemId: itemId })
                    });
                    
                    // Sucesso!
                    this.showMessage(data.message, true);
                    
                    // Melhoria Robustez: Atualiza o estado do ouro
                    this.state.playerGold = data.newGold;
                    this.ui.goldDisplay.textContent = data.newGold;
                    this.updateStatsUI(data.newStats);
                    
                    // Melhoria Robustez: Re-avalia todos os botões
                    this.updateButtonStates();

                } catch (error) {
                    // Falha (seja do sendRequest ou da lógica do servidor)
                    this.showMessage(error.message, false);
                } finally {
                    // Reabilita o botão APENAS se o jogador puder comprá-lo novamente
                    // (útil para itens empilháveis como poções)
                    const card = button.closest('.item-card');
                    const cost = parseInt(card.dataset.cost);
                    
                    if (this.state.playerGold >= cost) {
                         button.disabled = false;
                    }
                    button.textContent = 'COMPRAR';
                }
            }
        };

        app.init();
    });
    </script>
</body>
</html>