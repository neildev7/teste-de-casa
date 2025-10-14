<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Batalha Final | {{ $character->name }}</title>
<link rel="icon" href="{{ asset('img/logo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

<style>
    :root {
        --bg-dark: #1a1c2c; --ui-main: #5a3a2b; --ui-border-light: #a18c7c;
        --ui-border-dark: #3f2a1f; --text-light: #ffffff; --text-highlight: #ffc800;
        --hp-color: #e53935; --mp-color: #1e88e5; --xp-color: #fdd835;
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Press Start 2P', cursive;
        background: url("{{ asset('img/giphy3.gif') }}") no-repeat center center fixed;
        background-size: cover; min-height: 100vh;
        display: flex; justify-content: center; align-items: center;
        padding: 10px; background-color: var(--bg-dark);
        background-blend-mode: multiply; color: var(--text-light);
        image-rendering: pixelated;
    }
    #story-intro { position: fixed; inset: 0; background: #000; color: #fff; display: flex; flex-direction: column; justify-content: center; align-items: center; z-index: 200; padding: 20px; opacity: 1; transition: opacity 1s ease-out; }
    #story-text { font-size: 1.5rem; line-height: 1.8; max-width: 800px; text-align: center; }
    #stage-title { font-size: 3rem; color: var(--text-highlight); margin-top: 40px; opacity: 0; transform: scale(0.5); text-shadow: 3px 3px #000; }
    #stage-title.visible { animation: stage-intro 1.5s forwards; }

    .battle-screen { visibility: hidden; opacity: 0; width: 100%; max-width: 1200px; display: flex; flex-direction: column; align-items: center; gap: 15px; transition: opacity 1s; z-index: 2; }
    .battle-screen.visible { visibility: visible; opacity: 1; }
    
    .combatants-area { display: flex; justify-content: center; align-items: flex-start; gap: 20px; width: 100%; }
    
    .combatant-card {
        width: 320px; background: var(--ui-main);
        border: 4px solid var(--ui-border-dark);
        box-shadow: inset 0 0 0 4px var(--ui-border-light);
        padding: 15px; display: flex; flex-direction: column; align-items: center;
    }
    .combatant-card h2 { color: var(--text-highlight); font-size: 1.2rem; margin-bottom: 10px; text-shadow: 2px 2px #000; }
    .combatant-avatar { width: 120px; height: 120px; border: 4px solid var(--ui-border-light); margin-bottom: 15px; object-fit: cover; background-color: var(--bg-dark); }
    .stat-bar { width: 100%; height: 20px; background-color: var(--bg-dark); border: 2px solid var(--ui-border-light); margin-bottom: 8px; position: relative; }
    .stat-bar-fill { height: 100%; transition: width 0.5s ease-out; }
    .stat-bar-fill.hp { background: var(--hp-color); } .stat-bar-fill.mp { background: var(--mp-color); } .stat-bar-fill.xp { background: var(--xp-color); }
    .stat-bar-text { position: absolute; inset: 0; font-size: 0.8rem; color: white; text-shadow: 1px 1px #000; line-height: 18px; text-align: center; }
    
    .player-inventory { width: 100%; margin-top: 10px; padding-top: 10px; border-top: 2px solid var(--ui-border-light); display: flex; justify-content: space-around; font-size: 0.9rem; }
    .inventory-item { display: flex; align-items: center; gap: 8px; }

    .actions-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; width: 100%; }
    .action-btn { background: var(--ui-border-light); color: var(--ui-border-dark); border: 2px solid var(--ui-border-dark); padding: 10px 5px; font-weight: 700; font-size: 0.8rem; transition: all 0.1s; cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 8 8"><path fill="%23ffc800" d="M0 0v8l4-4-4-4z"/></svg>') 8 8, auto; font-family: 'Press Start 2P', cursive; }
    .action-btn:hover:not(:disabled) { background: var(--text-highlight); }
    .action-btn:active:not(:disabled) { transform: translateY(2px); }
    .action-btn:disabled { background: #555; color: #999; cursor: not-allowed; border-color: #333; }

    .battle-log { width: 100%; max-width: 800px; height: 150px; background: var(--ui-main); border: 4px solid var(--ui-border-dark); box-shadow: inset 0 0 0 4px var(--ui-border-light); padding: 15px; overflow-y: auto; color: var(--text-light); font-size: 0.9rem; line-height: 1.6; text-align: left; }
    .battle-log::-webkit-scrollbar { width: 12px; } .battle-log::-webkit-scrollbar-track { background: var(--ui-border-dark); } .battle-log::-webkit-scrollbar-thumb { background: var(--ui-border-light); border: 2px solid var(--ui-border-dark); }
    
    .log-player { color: #87ceeb; } .log-enemy { color: #f08080; } .log-system { color: #fafad2; } .log-heal { color: #7cb342; } .log-crit, .log-lvlup { color: var(--text-highlight); }
    .log-blessing { color: #ffeb3b; text-shadow: 0 0 4px #ffeb3b; animation: pulse-blessing 1s infinite; }
    .log-drain { color: #9c27b0; }

    .damage-popup { position: absolute; top: 30%; left: 50%; transform: translateX(-50%); font-size: 2rem; color: #ff4500; text-shadow: 2px 2px #000; animation: damagePopup 1s forwards; pointer-events: none; } .crit { color: var(--text-highlight); } .heal { color: #7cb342; } .shake { animation: shake 0.4s; } .flash-red { animation: flashRed 0.2s; }
    .modal-overlay { position: fixed; inset: 0; z-index: 102; display: flex; justify-content: center; align-items: center; background: rgba(0,0,0,0.8); opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .modal-overlay.is-visible { opacity: 1; visibility: visible; }
    .modal-box { background: var(--ui-main); border: 4px solid var(--ui-border-dark); box-shadow: inset 0 0 0 4px var(--ui-border-light); padding: 30px; max-width: 600px; width: 90%; color: var(--text-light); transform: scale(0.9); transition: transform 0.3s ease; text-align: center;}
    .modal-overlay.is-visible .modal-box { transform: scale(1); }
    .modal-box h2 { font-size: 1.5rem; margin-bottom: 20px; color: var(--text-highlight); text-shadow: 2px 2px #000; }
    .modal-box p { font-size: 1rem; line-height: 1.6; min-height: 3em; }
    .modal-box a { margin-top: 30px; padding: 10px 20px; background-color: var(--ui-border-light); color: var(--ui-border-dark); text-decoration: none; border: 2px solid var(--ui-border-dark); }

    @keyframes damagePopup { 0% { transform: translate(-50%, 0); opacity: 1; } 100% { transform: translate(-50%, -80px); opacity: 0; } } @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 50% { transform: translateX(5px); } 75% { transform: translateX(-5px); } }
    @keyframes flashRed { 50% { filter: brightness(3); } }
    @keyframes stage-intro { 0% { opacity: 0; transform: scale(0.5); } 70% { opacity: 1; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
    @keyframes pulse-blessing { 50% { filter: brightness(1.5); } }
    @media (max-width: 768px) { .combatants-area { flex-direction: column; align-items: center; gap: 20px; } .combatant-card { width: 100%; max-width: 400px; } }
</style>
</head>
<body>

<div id="story-intro">
    <p id="story-text"></p>
    <h1 id="stage-title">FASE FINAL</h1>
</div>

<div class="battle-screen">
    <div class="combatants-area">
        <div class="combatant-card" id="playerCard">
            <h2 id="playerName"></h2>
            <img src="{{ asset($character->avatar) }}" class="combatant-avatar" alt="Avatar do Jogador">
            <div class="stat-bar"><div class="stat-bar-fill hp" id="playerHpBar"></div><div class="stat-bar-text" id="playerHpText"></div></div>
            <div class="stat-bar"><div class="stat-bar-fill mp" id="playerMpBar"></div><div class="stat-bar-text" id="playerMpText"></div></div>
            <div class="stat-bar"><div class="stat-bar-fill xp" id="playerXpBar"></div><div class="stat-bar-text" id="playerXpText"></div></div>
            <div class="player-inventory">
                <div class="inventory-item">OURO: <span id="playerGold"></span></div>
                <div class="inventory-item">POÇÕES: <span id="playerPotions"></span></div>
            </div>
            <div class="actions-grid" id="actionsGrid"></div>
        </div>
        <div class="combatant-card" id="enemyCard">
            <h2 id="enemyName"></h2>
            <img src="" class="combatant-avatar" id="enemyAvatar" alt="Avatar do Inimigo">
            <div class="stat-bar"><div class="stat-bar-fill hp" id="enemyHpBar"></div><div class="stat-bar-text" id="enemyHpText"></div></div>
        </div>
    </div>
    <div class="battle-log" id="battleLog"></div>
</div>

<div class="modal-overlay" id="endgameModal">
    <div class="modal-box">
        <h2 id="modalTitle"></h2>
        <p id="modalText"></p>
        <a href="{{ route('home') }}" class="btn">REINICIAR</a>
    </div>
</div>

<script>
const storyText = `APÓS SOBREVIVER ÀS TERRAS SOMBRIAS, {{ $character->name }} CHEGA AO COVIL DO MAL SUPREMO. O AR CREPITA COM PODER PROFANO. ESTA É A BATALHA FINAL...`;

const Intro = {
    storyContainer: document.getElementById('story-intro'), storyTextEl: document.getElementById('story-text'), stageTitleEl: document.getElementById('stage-title'), battleScreenEl: document.querySelector('.battle-screen'),
    typewriter(text, i=0) { if (i < text.length) { this.storyTextEl.innerHTML += text.charAt(i); setTimeout(() => this.typewriter(text, i + 1), 50); } else { setTimeout(() => this.showStageTitle(), 2000); } },
    showStageTitle() { this.stageTitleEl.classList.add('visible'); setTimeout(() => this.hideIntro(), 2500); },
    hideIntro() { this.storyContainer.style.opacity = '0'; this.storyContainer.addEventListener('transitionend', () => { this.storyContainer.remove(); this.showBattleScreen(); }, { once: true }); },
    showBattleScreen() { this.battleScreenEl.classList.add('visible'); Game.init(); },
    start() { this.typewriter(storyText); }
};

const Game = {
    state: {
        player: {
            name: "{{ $character->name }}",
            hp: "{{ $character->hp }}", maxHp: "{{ $character->max_hp }}",
            mp: "{{ $character->mp }}", maxMp: "{{ $character->max_mp }}",
            attack: "{{ $character->attack }}", defense: "{{ $character->defense }}",
            sp_attack: "{{ $character->special_attack }}", sp_defense: "{{ $character->special_defense }}",
            speed: "{{ $character->speed }}",
            level: "{{ $character->level }}", 
            xp: "{{ $character->exp ?? 0 }}",
            gold: "{{ $character->gold ?? 0 }}",
            potions: "{{ $character->potions ?? 3 }}",
            xpToNextLevel: 9999,
            statusEffects: {}
        },
        enemy: {},
        enemies: [
            { name:"Behemoth Ancestral", hp: 600, attack: 70, defense: 60, xp: 1000, gold: 500, specialChance: 0.3, specialMoves: [{ type: 'stomp', power: 1.6 }], img: "{{ asset('img/behemot.png') }}" },
            { name:"Arquimaga do Caos", hp: 450, attack: 50, defense: 40, xp: 1500, gold: 750, specialChance: 0.5, sp_defense: 25, specialMoves: [{ type: 'meteor', sp_attack: 120 }], img: "{{ asset('img/maga-sombria.png') }}" },
            { name:"Rei do Vazio", hp: 999, attack: 110, defense: 70, xp: 0, gold: 0, specialChance: 0.4, specialMoves: [{ type: 'voidRift', power: 2.5, drain: 50 }], img: "{{ asset('img/rei-do-vazio.png') }}" }
        ],
        currentEnemyIndex: 0,
        gameState: 'PLAYER_TURN',
        blessingReceived: false
    },
    
    elements: {
        player: { card: document.getElementById('playerCard'), name: document.getElementById('playerName'), hpBar: document.getElementById('playerHpBar'), hpText: document.getElementById('playerHpText'), mpBar: document.getElementById('playerMpBar'), mpText: document.getElementById('playerMpText'), xpBar: document.getElementById('playerXpBar'), xpText: document.getElementById('playerXpText'), actions: document.getElementById('actionsGrid'), goldDisplay: document.getElementById('playerGold'), potionsDisplay: document.getElementById('playerPotions') },
        enemy: { card: document.getElementById('enemyCard'), name: document.getElementById('enemyName'), hpBar: document.getElementById('enemyHpBar'), hpText: document.getElementById('enemyHpText'), avatar: document.getElementById('enemyAvatar') },
        log: document.getElementById('battleLog'),
        modal: { container: document.getElementById('endgameModal'), title: document.getElementById('modalTitle'), text: document.getElementById('modalText') }
    },

    actions: {
        attack: { name: 'ATAQUE', cost: 0, type: 'mp', target: 'enemy', basePower: 1, stat: 'attack' },
        skill: { name: 'MAGIA', cost: 15, type: 'mp', target: 'enemy', basePower: 1.5, stat: 'sp_attack' },
        ultimate: { name: 'ESPECIAL', cost: 30, type: 'mp', target: 'enemy', basePower: 2.5, stat: 'sp_attack' },
        potion: { name: 'POÇÃO', cost: 1, type: 'potion', target: 'player', basePower: 100, stat: 'heal' },
    },

    init() {
        this.sanitizeStats();
        this.loadEnemy(); 
        this.renderActionButtons(); 
        this.updateUI();
        this.logMessage(`O MAL SE REVELA: ${this.state.enemy.name.toUpperCase()}!`, 'log-system');
    },

    sanitizeStats() {
        const p = this.state.player;
        const defaultValues = { hp: 100, maxHp: 100, mp: 50, maxMp: 50, attack: 10, defense: 5, sp_attack: 10, sp_defense: 5, speed: 10, level: 1, xp: 0, gold: 0, potions: 3 };
        for (const stat in defaultValues) {
            p[stat] = parseInt(p[stat], 10) || defaultValues[stat];
        }
        p.hp = Math.min(p.hp, p.maxHp);
    },

    calculateDamage(power, defense) {
        const effectiveDefense = (defense || 0) * 0.5;
        const baseDamage = Math.max(1, power - effectiveDefense);
        return Math.floor(baseDamage * (Math.random() * 0.4 + 0.8));
    },
    
    executeTurn(actionKey) {
        if (this.state.gameState !== 'PLAYER_TURN') return;
        const action = this.actions[actionKey];
        if (action.type === 'mp' && this.state.player.mp < action.cost) { this.logMessage('MP INSUFICIENTE!', 'log-system'); return; }
        if (action.type === 'potion' && this.state.player.potions <= 0) { this.logMessage('SEM POÇÕES!', 'log-system'); return; }
        this.setGameState('PROCESSING');
        if (action.type === 'mp') this.state.player.mp -= action.cost;
        if (action.type === 'potion') this.state.player.potions--;
        this.logMessage(`${this.state.player.name} USA ${action.name.split('(')[0].trim()}!`, 'log-player');
        
        if (action.stat === 'heal') {
            const healAmount = action.basePower;
            this.state.player.hp = Math.min(this.state.player.maxHp, this.state.player.hp + healAmount);
            this.showPopup(healAmount, this.elements.player.card, true);
            this.logMessage(`${this.state.player.name} RECUPERA ${healAmount} HP.`, 'log-heal');
        } else {
            const power = this.state.player[action.stat] * action.basePower;
            let damage = (action.stat === 'attack') ? this.calculateDamage(power, this.state.enemy.defense) : this.calculateDamage(power, this.state.enemy.sp_defense);
            let isCrit = false;
            if (Math.random() < 0.2) {
                damage = Math.floor(damage * 1.5);
                isCrit = true;
                this.logMessage('ACERTO CRÍTICO!', 'log-crit');
                this.elements.enemy.card.classList.add('shake');
                setTimeout(() => this.elements.enemy.card.classList.remove('shake'), 400);
            }
            this.state.enemy.hp -= damage;
            this.elements.enemy.card.classList.add('flash-red');
            setTimeout(() => this.elements.enemy.card.classList.remove('flash-red'), 200);
            this.showPopup(damage, this.elements.enemy.card, false, isCrit);
        }

        this.updateUI();
        if (this.state.enemy.hp <= 0) {
            const defeatedEnemy = this.state.enemies[this.state.currentEnemyIndex];
            this.logMessage(`${defeatedEnemy.name.toUpperCase()} DERROTADO!`, 'log-system');
            if (defeatedEnemy.gold > 0) { this.state.player.gold += defeatedEnemy.gold; this.logMessage(`+${defeatedEnemy.gold} OURO!`, 'log-crit'); }
            if (defeatedEnemy.xp > 0) this.gainXP(defeatedEnemy.xp);
            setTimeout(() => this.nextEnemy(), 2000);
            return;
        }
        setTimeout(() => this.enemyTurn(), 1500);
    },

    enemyTurn() {
        this.setGameState('ENEMY_TURN');
        const enemy = this.state.enemy;
        let damage = 0;
        if (enemy.specialMoves && Math.random() < enemy.specialChance) {
            const move = enemy.specialMoves[0];
            switch (move.type) {
                case 'stomp':
                    this.logMessage(`${enemy.name.toUpperCase()} USA TERREMOTO!`, 'log-enemy');
                    damage = this.calculateDamage(enemy.attack * move.power, this.state.player.defense);
                    break;
                case 'meteor':
                    this.logMessage(`${enemy.name.toUpperCase()} CONJURA METEORO!`, 'log-enemy');
                    damage = this.calculateDamage(move.sp_attack, this.state.player.sp_defense);
                    break;
                case 'voidRift':
                    this.logMessage(`${enemy.name.toUpperCase()} ABRE UMA FENDA DO VAZIO!`, 'log-enemy');
                    damage = this.calculateDamage(enemy.attack * move.power, this.state.player.defense);
                    const drained = Math.floor(damage * 0.5);
                    enemy.hp = Math.min(enemy.maxHp, enemy.hp + drained);
                    this.state.player.mp = Math.max(0, this.state.player.mp - move.drain);
                    this.logMessage(`SUA VIDA E MANA SÃO DRENADAS!`, 'log-drain');
                    break;
            }
        } else {
            damage = this.calculateDamage(enemy.attack, this.state.player.defense);
            this.logMessage(`${enemy.name.toUpperCase()} ATACA!`, 'log-enemy');
        }
        this.state.player.hp -= damage;
        this.elements.player.card.classList.add('flash-red');
        setTimeout(() => this.elements.player.card.classList.remove('flash-red'), 200);
        this.showPopup(damage, this.elements.player.card, false);
        this.updateUI();
        if(this.state.player.hp <= 0) { this.logMessage('VOCÊ FOI DERROTADO...', 'log-system'); this.gameOver(false); return; }
        setTimeout(() => this.setGameState('PLAYER_TURN'), 1000);
    },
    
    gainXP(amount) {
        if(amount <= 0) return;
        this.logMessage(`+${amount} XP!`, 'log-crit'); this.state.player.xp += amount;
        while (this.state.player.xp >= this.state.player.xpToNextLevel) {
            this.state.player.xp -= this.state.player.xpToNextLevel; this.state.player.level++;
            this.state.player.xpToNextLevel = Math.floor(this.state.player.xpToNextLevel * 1.8);
            this.state.player.maxHp += 25; this.state.player.maxMp += 20;
            this.state.player.attack += 7; this.state.player.defense += 5; this.state.player.sp_attack += 6;
            const healAmount = Math.floor(this.state.player.maxHp * 0.5);
            this.state.player.hp = Math.min(this.state.player.maxHp, this.state.player.hp + healAmount);
            this.state.player.mp = this.state.player.maxMp;
            this.logMessage(`LEVEL UP! NÍVEL ${this.state.player.level}!`, 'log-lvlup');
        }
        this.updateUI();
    },

    nextEnemy() {
        this.state.currentEnemyIndex++;
        if (this.state.currentEnemyIndex === 1 && !this.state.blessingReceived) {
            this.state.blessingReceived = true;
            this.logMessage('UMA ENERGIA ANCESTRAL FLUI POR SUAS VEIAS!', 'log-blessing');
            this.logMessage('BÊNÇÃO DIVINA: SEUS PODERES AUMENTARAM!', 'log-blessing');
            this.state.player.attack = Math.floor(this.state.player.attack * 1.5);
            this.state.player.defense = Math.floor(this.state.player.defense * 1.5);
            this.state.player.sp_attack = Math.floor(this.state.player.sp_attack * 1.5);
            this.state.player.maxHp = Math.floor(this.state.player.maxHp * 1.5);
            const healAmount = Math.floor(this.state.player.maxHp);
            this.state.player.hp = Math.min(this.state.player.maxHp, this.state.player.hp + healAmount);
            this.state.player.mp = this.state.player.maxMp;
        }
        if (this.state.currentEnemyIndex >= this.state.enemies.length) { this.gameOver(true); return; }
        this.loadEnemy();
        this.updateUI();
        this.logMessage(`DAS PROFUNDEZAS, EMERGE ${this.state.enemy.name.toUpperCase()}!`, 'log-system');
        this.setGameState('PLAYER_TURN');
    },
    
    loadEnemy() { this.state.enemy = { ...this.state.enemies[this.state.currentEnemyIndex] }; this.state.enemy.maxHp = this.state.enemy.hp; },
    
    gameOver(isVictory) {
        this.setGameState('GAME_OVER');
        const modal = this.elements.modal.container;
        if (isVictory) {
            modal.innerHTML = `<div class="modal-box"><h2 id="modalTitle">VITÓRIA LENDÁRIA</h2><p id="modalText">O segredo das trevas foi revelado...</p><a href="{{ route('home') }}" class="btn">REINICIAR</a></div>`;
            this.openModal(modal);
            setTimeout(() => {
                const secretMessage = "PROGRAMAÇÃO NÃO É MÁGICA!";
                const textElement = modal.querySelector('#modalText');
                let i = 0;
                textElement.textContent = "O SEGREDO É: ";
                const typingInterval = setInterval(() => {
                    if (i < secretMessage.length) { textElement.textContent += secretMessage.charAt(i); i++; }
                    else { clearInterval(typingInterval); }
                }, 150);
            }, 2000);
        } else {
            modal.innerHTML = `<div class="modal-box"><h2 id="modalTitle">FIM DE JOGO</h2><p id="modalText">AS TREVAS CONSUMIRAM O REINO...</p><a href="{{ route('home') }}" class="btn">REINICIAR</a></div>`;
            this.openModal(modal);
        }
    },
    
    openModal(modal) { modal.classList.add('is-visible'); },
    
    updateUI() {
        const { player, enemy } = this.state;
        const { player: playerEl, enemy: enemyEl } = this.elements;
        playerEl.name.textContent = `${player.name} LV ${player.level}`;
        playerEl.hpBar.style.width = `${Math.max(0, player.hp / player.maxHp * 100)}%`; playerEl.hpText.textContent = `HP: ${Math.max(0, Math.ceil(player.hp))}/${player.maxHp}`;
        playerEl.mpBar.style.width = `${Math.max(0, player.mp / player.maxMp * 100)}%`; playerEl.mpText.textContent = `MP: ${Math.max(0, Math.ceil(player.mp))}/${player.maxMp}`;
        playerEl.xpBar.style.width = `${Math.max(0, player.xp / player.xpToNextLevel * 100)}%`; playerEl.xpText.textContent = `XP: ${player.xp}/${player.xpToNextLevel}`;
        playerEl.goldDisplay.textContent = player.gold;
        playerEl.potionsDisplay.textContent = player.potions;
        enemyEl.name.textContent = enemy.name.toUpperCase(); enemyEl.avatar.src = enemy.img; enemyEl.hpBar.style.width = `${Math.max(0, enemy.hp / enemy.maxHp * 100)}%`; enemyEl.hpText.textContent = `HP: ${Math.max(0, Math.ceil(enemy.hp))}/${enemy.maxHp}`;
        const potionBtn = this.elements.player.actions.querySelector('[data-action-key="potion"]');
        if (potionBtn) { potionBtn.innerHTML = `POÇÃO (${player.potions})`; potionBtn.disabled = player.potions <= 0 || this.state.gameState !== 'PLAYER_TURN'; }
    },

    renderActionButtons() {
        this.elements.player.actions.innerHTML = '';
        for (const key in this.actions) { const action = this.actions[key]; const btn = document.createElement('button'); btn.className = 'action-btn'; btn.dataset.actionKey = key; btn.innerHTML = action.name; btn.onclick = () => this.executeTurn(key); this.elements.player.actions.appendChild(btn); }
    },
    
    logMessage(message, className = '') { const p = document.createElement('p'); p.innerHTML = message; if (className) p.className = className; this.elements.log.appendChild(p); this.elements.log.scrollTop = this.elements.log.scrollHeight; },
    
    showPopup(text, targetCard, isHeal = false, isCrit = false) { const popup = document.createElement('div'); popup.className = 'damage-popup'; popup.textContent = text; if (isHeal) popup.classList.add('heal'); if (isCrit) popup.classList.add('crit'); targetCard.appendChild(popup); setTimeout(() => popup.remove(), 1000); },
    
    setGameState(newState) { this.state.gameState = newState; const buttons = this.elements.player.actions.querySelectorAll('button'); buttons.forEach(btn => { if(btn.dataset.actionKey !== 'potion') { btn.disabled = (newState !== 'PLAYER_TURN'); } else { btn.disabled = (this.state.player.potions <= 0 || newState !== 'PLAYER_TURN'); } }); }
};

document.addEventListener('DOMContentLoaded', () => Intro.start());
</script>
</body>
</html>