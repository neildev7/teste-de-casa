<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Batalha Épica | {{ $character->name }}</title>
<link rel="icon" href="{{ asset('img/logo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=IM+Fell+English:ital@0;1&family=IM+Fell+English+SC&display=swap" rel="stylesheet">

<style>
    :root {
        --wood-color: #5d4037;
        --metal-color: #a9a9a9;
        --gold-color: #ffd700;
        --text-light: #f0e9d9;
        --hp-color: #9e2b25;
        --mp-color: #3b5a9d;
        --xp-color: #c7923e;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'IM Fell English', serif;
        background: url("{{ asset('img/giphy.gif') }}") no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        overflow: hidden;
    }
    
    .overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: -1; }

    .battle-screen {
        width: 100%;
        max-width: 1200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        animation: fadeIn 1s forwards;
    }
    
    .combatants-area { display: flex; justify-content: center; align-items: flex-start; gap: 50px; width: 100%; }
    .combatant-card { position: relative; width: 350px; background-color: var(--wood-color); border: 10px solid; border-image: linear-gradient(45deg, var(--metal-color), #8b8b8b) 1; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6); color: var(--text-light); padding: 20px; display: flex; flex-direction: column; align-items: center; text-align: center; transition: transform 0.3s ease; }
    .combatant-card h2 { font-family: 'IM Fell English SC', serif; color: var(--gold-color); font-size: 1.8rem; margin-bottom: 10px; }
    .combatant-avatar { width: 150px; height: 150px; border-radius: 50%; border: 4px solid var(--gold-color); margin-bottom: 15px; object-fit: cover; background-color: #222; }
    .stat-bar { width: 100%; height: 22px; background-color: rgba(0, 0, 0, 0.4); border: 1px solid var(--metal-color); border-radius: 5px; margin-bottom: 8px; position: relative; overflow: hidden; }
    .stat-bar-fill { height: 100%; border-radius: 4px; transition: width 0.5s ease-out; }
    .stat-bar-fill.hp { background: var(--hp-color); }
    .stat-bar-fill.mp { background: var(--mp-color); }
    .stat-bar-fill.xp { background: var(--xp-color); }
    .stat-bar-text { position: absolute; inset: 0; font-family: 'Cinzel', serif; font-weight: 700; font-size: 0.8rem; color: white; text-shadow: 1px 1px 2px black; line-height: 22px; }
    .actions-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; width: 100%; }
    .action-btn { background: var(--metal-color); color: var(--wood-color); border: 3px outset var(--metal-color); padding: 12px 5px; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: all 0.2s ease-in-out; cursor: pointer; font-family: 'Cinzel', serif; }
    .action-btn:hover:not(:disabled) { background: var(--gold-color); transform: translateY(-2px); }
    .action-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .battle-log { width: 100%; max-width: 800px; height: 180px; background: rgba(0,0,0,0.6); border: 2px solid var(--metal-color); padding: 15px; overflow-y: auto; color: var(--text-light); font-size: 1.1rem; line-height: 1.6; scroll-behavior: smooth; }
    .battle-log p { margin-bottom: 5px; }
    .log-player { color: #87ceeb; }
    .log-enemy { color: #f08080; }
    .log-system { color: #fafad2; font-style: italic; }
    .log-heal { color: #90ee90; }
    .log-crit { color: var(--gold-color); font-weight: bold; }
    .log-lvlup { color: var(--gold-color); font-family: 'Cinzel', serif; font-size: 1.2rem; }
    .damage-popup { position: absolute; top: 30%; left: 50%; transform: translateX(-50%); font-family: 'Cinzel', serif; font-size: 2.5rem; font-weight: bold; color: #ff4500; text-shadow: 2px 2px 2px black; animation: damagePopup 1s forwards; pointer-events: none; }
    .crit { color: var(--gold-color); }
    .heal { color: #90ee90; }
    .shake { animation: shake 0.4s; }
    .flash-red { animation: flashRed 0.4s; }
    .endgame-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.8); display: flex; justify-content: center; align-items: center; z-index: 100; opacity: 0; pointer-events: none; transition: opacity 0.5s; }
    .endgame-modal.visible { opacity: 1; pointer-events: all; }
    .modal-content { background-color: var(--wood-color); border: 10px solid var(--gold-color); padding: 40px; text-align: center; animation: fadeIn 0.5s; }
    .modal-content h2 { font-family: 'IM Fell English SC', serif; font-size: 4rem; color: var(--gold-color); }
    .modal-content p { color: var(--text-light); font-size: 1.5rem; margin: 20px 0; }
    .modal-content a { display: inline-block; margin-top: 20px; background: var(--gold-color); color: var(--wood-color); padding: 15px 30px; font-family: 'Cinzel', serif; text-decoration: none; font-weight: bold; }
    
    @keyframes fadeIn { to { opacity: 1; } }
    @keyframes damagePopup { 0% { transform: translate(-50%, 0); opacity: 1; } 100% { transform: translate(-50%, -80px); opacity: 0; } }
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 50% { transform: translateX(5px); } 75% { transform: translateX(-5px); } }
    @keyframes flashRed { 0%, 100% { background-color: var(--wood-color); } 50% { background-color: #581111; } }
</style>
</head>
<body>

<div class="overlay"></div>

<div class="battle-screen">
    <div class="combatants-area">
        <div class="combatant-card" id="playerCard">
            <h2 id="playerName">{{ $character->name }}</h2>
            <img src="{{ asset($character->avatar) }}" class="combatant-avatar" alt="Avatar do Jogador">
            
            <div class="stat-bar"><div class="stat-bar-fill hp" id="playerHpBar"></div><div class="stat-bar-text" id="playerHpText"></div></div>
            <div class="stat-bar"><div class="stat-bar-fill mp" id="playerMpBar"></div><div class="stat-bar-text" id="playerMpText"></div></div>
            <div class="stat-bar"><div class="stat-bar-fill xp" id="playerXpBar"></div><div class="stat-bar-text" id="playerXpText"></div></div>

            <div class="actions-grid" id="actionsGrid">
            </div>
        </div>

        <div class="combatant-card" id="enemyCard">
            <h2 id="enemyName">Goblin</h2>
            <img src="{{ asset('img/goblin.png') }}" class="combatant-avatar" id="enemyAvatar" alt="Avatar do Inimigo">
            <div class="stat-bar"><div class="stat-bar-fill hp" id="enemyHpBar"></div><div class="stat-bar-text" id="enemyHpText"></div></div>
        </div>
    </div>

    <div class="battle-log" id="battleLog"></div>
</div>

<div class="endgame-modal" id="endgameModal">
    <div class="modal-content">
        <h2 id="modalTitle"></h2>
        <p id="modalText"></p>
        <a href="{{ route('home') }}">Voltar para o Guild Hall</a>
    </div>
</div>

<script>
const Game = {
    state: {
        player: {
            name: "{{ $character->name }}",
            hp: {{ $character->hp }}, maxHp: {{ $character->hp }},
            mp: {{ $character->mp }}, maxMp: {{ $character->mp }},
            attack: {{ $character->attack }}, defense: {{ $character->defense }},
            sp_attack: {{ $character->special_attack }}, sp_defense: {{ $character->special_defense }},
            speed: {{ $character->speed }},
            level: {{ $character->level }}, xp: {{ $character->exp }}, // Pega o XP inicial do banco
            xpToNextLevel: 50,
            potions: 3
        },
        enemy: {},
        enemies: [
            { name:"Goblin", hp:30, attack:8, defense:3, speed: 10, img:"{{ asset('img/goblin.png') }}", xp: 25, specialChance: 0.1 },
            { name:"Orc", hp:60, attack:12, defense:6, speed: 8, img:"{{ asset('img/orc.png') }}", xp: 50, specialChance: 0.25 },
            { name:"Dragão", hp:100, attack:18, defense:10, speed: 12, img:"{{ asset('img/dragao.png') }}", xp: 100, specialChance: 0.4 }
        ],
        currentEnemyIndex: 0,
        gameState: 'PLAYER_TURN',
    },

    elements: { player: { card: document.getElementById('playerCard'), name: document.getElementById('playerName'), hpBar: document.getElementById('playerHpBar'), hpText: document.getElementById('playerHpText'), mpBar: document.getElementById('playerMpBar'), mpText: document.getElementById('playerMpText'), xpBar: document.getElementById('playerXpBar'), xpText: document.getElementById('playerXpText'), actions: document.getElementById('actionsGrid') }, enemy: { card: document.getElementById('enemyCard'), name: document.getElementById('enemyName'), hpBar: document.getElementById('enemyHpBar'), hpText: document.getElementById('enemyHpText'), avatar: document.getElementById('enemyAvatar') }, log: document.getElementById('battleLog'), modal: { container: document.getElementById('endgameModal'), title: document.getElementById('modalTitle'), text: document.getElementById('modalText') } },
    actions: { attack: { name: 'Ataque Físico ⚔️', cost: 0, type: 'mp', target: 'enemy', basePower: 1, stat: 'attack' }, skill: { name: 'Feitiço Menor ✨ (10MP)', cost: 10, type: 'mp', target: 'enemy', basePower: 1.5, stat: 'sp_attack' }, ultimate: { name: 'Explosão Arcana 🔥 (25MP)', cost: 25, type: 'mp', target: 'enemy', basePower: 2.5, stat: 'sp_attack' }, potion: { name: 'Usar Poção ❤️', cost: 1, type: 'potion', target: 'player', basePower: 40, stat: 'heal' }, },
    init() { this.loadEnemy(); this.renderActionButtons(); this.updateUI(); this.logMessage('Um ' + this.state.enemy.name + ' selvagem aparece!', 'log-system'); },
    executeTurn(actionKey) { if (this.state.gameState !== 'PLAYER_TURN') return; this.setGameState('PROCESSING'); const action = this.actions[actionKey]; if (action.type === 'mp' && this.state.player.mp < action.cost) { this.logMessage('MP insuficiente!', 'log-system'); this.setGameState('PLAYER_TURN'); return; } if (action.type === 'potion' && this.state.player.potions < action.cost) { this.logMessage('Sem poções!', 'log-system'); this.setGameState('PLAYER_TURN'); return; } if (action.type === 'mp') this.state.player.mp -= action.cost; if (action.type === 'potion') this.state.player.potions--; this.logMessage(`${this.state.player.name} usou ${action.name}!`, 'log-player'); let value = 0; let isCrit = false; if (action.stat === 'heal') { value = action.basePower; this.state.player.hp = Math.min(this.state.player.maxHp, this.state.player.hp + value); this.showPopup(value, this.elements.player.card, true); this.logMessage(`${this.state.player.name} recuperou ${value} de HP.`, 'log-heal'); } else { const power = this.state.player[action.stat] * action.basePower; const defense = (action.stat === 'attack') ? this.state.enemy.defense : 0; let damage = Math.floor(Math.max(1, (power - defense) * (Math.random() * 0.4 + 0.8))); if (Math.random() < 0.15) { damage = Math.floor(damage * 1.5); isCrit = true; this.logMessage('Acerto Crítico!', 'log-crit'); document.body.classList.add('shake'); setTimeout(() => document.body.classList.remove('shake'), 400); } this.state.enemy.hp -= damage; this.elements.enemy.card.classList.add('flash-red'); setTimeout(() => this.elements.enemy.card.classList.remove('flash-red'), 400); this.showPopup(damage, this.elements.enemy.card, false, isCrit); } this.updateUI(); if (this.state.enemy.hp <= 0) { this.logMessage(`${this.state.enemy.name} foi derrotado!`, 'log-system'); this.gainXP(this.state.enemy.xp); setTimeout(() => this.nextEnemy(), 1500); return; } setTimeout(() => this.enemyTurn(), 1500); },
    enemyTurn() { this.setGameState('ENEMY_TURN'); this.logMessage(`Turno de ${this.state.enemy.name}.`, 'log-system'); let damage; if (Math.random() < this.state.enemy.specialChance) { damage = Math.floor(Math.max(1, (this.state.enemy.attack * 1.5 - this.state.player.defense) * (Math.random() * 0.4 + 0.8))); this.logMessage(`${this.state.enemy.name} usa um ATAQUE PODEROSO!`, 'log-enemy'); } else { damage = Math.floor(Math.max(1, (this.state.enemy.attack - this.state.player.defense) * (Math.random() * 0.4 + 0.8))); this.logMessage(`${this.state.enemy.name} ataca!`, 'log-enemy'); } this.state.player.hp -= damage; this.elements.player.card.classList.add('flash-red'); setTimeout(() => this.elements.player.card.classList.remove('flash-red'), 400); this.showPopup(damage, this.elements.player.card, false); this.state.player.mp = Math.min(this.state.player.maxMp, this.state.player.mp + 5); this.updateUI(); if (this.state.player.hp <= 0) { this.logMessage('Você foi derrotado...', 'log-system'); this.gameOver(false); return; } setTimeout(() => this.setGameState('PLAYER_TURN'), 1000); },
    gainXP(amount) { this.logMessage(`${this.state.player.name} ganhou ${amount} XP!`, 'log-system'); this.state.player.xp += amount; while (this.state.player.xp >= this.state.player.xpToNextLevel) { this.state.player.xp -= this.state.player.xpToNextLevel; this.state.player.level++; this.state.player.xpToNextLevel = Math.floor(this.state.player.xpToNextLevel * 1.5); this.state.player.maxHp += 15; this.state.player.maxMp += 10; this.state.player.attack += 3; this.state.player.defense += 2; this.state.player.sp_attack += 3; this.state.player.hp = this.state.player.maxHp; this.state.player.mp = this.state.player.maxMp; this.logMessage(`LEVEL UP! Você alcançou o Nível ${this.state.player.level}!`, 'log-lvlup'); } this.updateUI(); },
    nextEnemy() { this.state.currentEnemyIndex++; if (this.state.currentEnemyIndex >= this.state.enemies.length) { this.gameOver(true); return; } this.loadEnemy(); this.updateUI(); this.logMessage(`Um novo oponente surge: ${this.state.enemy.name}!`, 'log-system'); this.setGameState('PLAYER_TURN'); },
    loadEnemy() { this.state.enemy = { ...this.state.enemies[this.state.currentEnemyIndex] }; this.state.enemy.maxHp = this.state.enemy.hp; },
    
    async gameOver(isVictory) {
        this.setGameState('GAME_OVER');
        if (isVictory) {
            this.logMessage('Todos os inimigos foram derrotados! Salvando progresso...', 'log-lvlup');
            
            await this.saveProgress();
            
            this.logMessage('Redirecionando...', 'log-system');
            setTimeout(() => { 
                window.location.href = "{{ route('character.play2', $character->id) }}"; 
            }, 1000);

        } else {
            this.elements.modal.title.textContent = "Fim de Jogo";
            this.elements.modal.text.textContent = "Sua jornada termina aqui...";
            this.elements.modal.container.classList.add('visible');
        }
    },

    async saveProgress() {
        const playerData = this.state.player;
        
        try {
            await fetch("{{ route('character.saveProgress', $character->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(playerData)
            });
        } catch (error) {
            console.error('Erro ao salvar o progresso:', error);
            this.logMessage('Falha ao salvar o progresso. Tente novamente.', 'log-enemy');
        }
    },

    updateUI() { const { player, enemy } = this.state; const { player: playerEl, enemy: enemyEl } = this.elements; playerEl.name.textContent = `${player.name} (Lvl ${player.level})`; playerEl.hpBar.style.width = `${Math.max(0, player.hp / player.maxHp * 100)}%`; playerEl.hpText.textContent = `HP: ${Math.max(0, player.hp)} / ${player.maxHp}`; playerEl.mpBar.style.width = `${Math.max(0, player.mp / player.maxMp * 100)}%`; playerEl.mpText.textContent = `MP: ${Math.max(0, player.mp)} / ${player.maxMp}`; playerEl.xpBar.style.width = `${Math.max(0, player.xp / player.xpToNextLevel * 100)}%`; playerEl.xpText.textContent = `XP: ${player.xp} / ${player.xpToNextLevel}`; enemyEl.name.textContent = enemy.name; enemyEl.avatar.src = enemy.img; enemyEl.hpBar.style.width = `${Math.max(0, enemy.hp / enemy.maxHp * 100)}%`; enemyEl.hpText.textContent = `HP: ${Math.max(0, enemy.hp)} / ${enemy.maxHp}`; },
    renderActionButtons() { this.elements.player.actions.innerHTML = ''; for (const key in this.actions) { const action = this.actions[key]; const btn = document.createElement('button'); btn.className = 'action-btn'; btn.textContent = action.name; btn.onclick = () => this.executeTurn(key); this.elements.player.actions.appendChild(btn); } },
    logMessage(message, className = '') { const p = document.createElement('p'); p.innerHTML = message; if (className) p.className = className; this.elements.log.appendChild(p); this.elements.log.scrollTop = this.elements.log.scrollHeight; },
    showPopup(text, targetCard, isHeal = false, isCrit = false) { const popup = document.createElement('div'); popup.className = 'damage-popup'; popup.textContent = text; if (isHeal) popup.classList.add('heal'); if (isCrit) popup.classList.add('crit'); targetCard.appendChild(popup); setTimeout(() => popup.remove(), 1000); },
    setGameState(newState) { this.state.gameState = newState; const buttons = this.elements.player.actions.querySelectorAll('button'); buttons.forEach(btn => btn.disabled = (newState !== 'PLAYER_TURN')); }
};

document.addEventListener('DOMContentLoaded', () => Game.init());
</script>
</body>
</html>