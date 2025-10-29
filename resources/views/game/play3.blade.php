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
        --heal-color: #7cb342; --blessing-color: #ffeb3b; --drain-color: #9c27b0;
        --cursor-pointer: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 8 8"><path fill="%23ffc800" d="M0 0v8l4-4-4-4z"/></svg>') 8 8, auto;
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
        overflow: hidden;
    }

    /* --- ESTILOS DA INTRODUÇÃO --- */
    #story-intro {
        position: fixed; inset: 0; background: #000; color: #fff;
        display: flex; flex-direction: column; justify-content: center;
        align-items: center; z-index: 200; padding: 20px;
        opacity: 1; transition: opacity 1s ease-out;
        cursor: var(--cursor-pointer); outline: none;
    }
    #story-text { font-size: 1.5rem; line-height: 1.8; max-width: 800px; text-align: center; }
    #stage-title {
        font-size: 3rem; color: var(--text-highlight); margin-top: 40px;
        opacity: 0; transform: scale(0.5); text-shadow: 3px 3px #000;
    }
    #stage-title.visible { animation: stage-intro 1.5s forwards; }
    .intro-prompt {
        position: absolute; bottom: 30px; right: 30px;
        font-size: 1rem; color: var(--text-light);
        animation: blink 1.5s infinite steps(1);
    }
    .battle-screen { visibility: hidden; opacity: 0; width: 100%; max-width: 1200px; display: flex; flex-direction: column; align-items: center; gap: 15px; transition: opacity 1s; z-index: 2; }
    .battle-screen.visible { visibility: visible; opacity: 1; }
    /* --- FIM DOS ESTILOS DA INTRODUÇÃO --- */

    .combatants-area { display: flex; justify-content: center; align-items: flex-start; gap: 20px; width: 100%; }
    .combatant-card {
        width: 320px; background: var(--ui-main);
        border: 4px solid var(--ui-border-dark);
        box-shadow: inset 0 0 0 4px var(--ui-border-light);
        padding: 15px; display: flex; flex-direction: column; align-items: center;
        position: relative;
    }
    .combatant-card h2 { color: var(--text-highlight); font-size: 1.2rem; margin-bottom: 10px; text-shadow: 2px 2px #000; }
    .combatant-avatar { width: 120px; height: 120px; border: 4px solid var(--ui-border-light); margin-bottom: 15px; object-fit: cover; background-color: var(--bg-dark); }
    
    .status-effects { /* Novo */
        position: absolute; top: 80px; left: 15px;
        display: flex; flex-direction: column; gap: 5px;
    }
    .status-icon {
        width: 24px; height: 24px;
        border: 2px solid var(--ui-border-light);
        font-size: 0.7rem; color: white;
        display: flex; justify-content: center; align-items: center;
        text-shadow: 1px 1px #000;
    }
    .status-icon.blessing { background-color: var(--blessing-color); color: #000; }
    .status-icon.drain { background-color: var(--drain-color); }

    .stat-bar { width: 100%; height: 20px; background-color: var(--bg-dark); border: 2px solid var(--ui-border-light); margin-bottom: 8px; position: relative; }
    .stat-bar-fill { height: 100%; transition: width 0.5s ease-out; }
    .stat-bar-fill.hp { background: var(--hp-color); } .stat-bar-fill.mp { background: var(--mp-color); } .stat-bar-fill.xp { background: var(--xp-color); }
    .stat-bar-text { position: absolute; inset: 0; font-size: 0.8rem; color: white; text-shadow: 1px 1px #000; line-height: 18px; text-align: center; }
    
    .player-inventory { width: 100%; margin-top: 10px; padding-top: 10px; border-top: 2px solid var(--ui-border-light); display: flex; justify-content: space-around; font-size: 0.9rem; }
    .inventory-item { display: flex; align-items: center; gap: 8px; }

    .actions-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px; width: 100%; }
    .action-btn { 
        background: var(--ui-border-light); color: var(--ui-border-dark); 
        border: 2px solid var(--ui-border-dark); padding: 10px 5px; 
        font-weight: 700; font-size: 0.8rem; transition: all 0.1s; 
        cursor: var(--cursor-pointer); font-family: 'Press Start 2P', cursive;
        outline: none;
    }
    .action-btn:hover:not(:disabled) { background: var(--text-highlight); }
    .action-btn:focus-visible {
        background: var(--text-highlight);
        box-shadow: 0 0 0 2px var(--bg-dark), 0 0 0 4px var(--text-highlight);
    }
    .action-btn:active:not(:disabled) { transform: translateY(2px); }
    .action-btn:disabled { background: #555; color: #999; cursor: not-allowed; border-color: #333; }

    .battle-log { width: 100%; max-width: 800px; height: 150px; background: var(--ui-main); border: 4px solid var(--ui-border-dark); box-shadow: inset 0 0 0 4px var(--ui-border-light); padding: 15px; overflow-y: auto; color: var(--text-light); font-size: 0.9rem; line-height: 1.6; text-align: left; }
    .battle-log::-webkit-scrollbar { width: 12px; } .battle-log::-webkit-scrollbar-track { background: var(--ui-border-dark); } .battle-log::-webkit-scrollbar-thumb { background: var(--ui-border-light); border: 2px solid var(--ui-border-dark); }
    
    .log-player { color: #87ceeb; } .log-enemy { color: #f08080; } .log-system { color: #fafad2; } .log-heal { color: var(--heal-color); } .log-crit, .log-lvlup { color: var(--text-highlight); }
    .log-blessing { color: var(--blessing-color); text-shadow: 0 0 4px var(--blessing-color); animation: pulse-blessing 1s infinite; }
    .log-drain { color: var(--drain-color); }

    .damage-popup { position: absolute; top: 30%; left: 50%; transform: translateX(-50%); font-size: 2rem; color: #ff4500; text-shadow: 2px 2px #000; animation: damagePopup 1s forwards; pointer-events: none; } .crit { color: var(--text-highlight); } .heal { color: var(--heal-color); } .miss { color: #999; font-size: 1.5rem; }
    .shake { animation: shake 0.4s; } .flash-red { animation: flashRed 0.2s; }
    
    .modal-overlay { position: fixed; inset: 0; z-index: 102; display: flex; justify-content: center; align-items: center; background: rgba(0,0,0,0.8); opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .modal-overlay.is-visible { opacity: 1; visibility: visible; }
    .modal-box { background: var(--ui-main); border: 4px solid var(--ui-border-dark); box-shadow: inset 0 0 0 4px var(--ui-border-light); padding: 30px; max-width: 600px; width: 90%; color: var(--text-light); transform: scale(0.9); transition: transform 0.3s ease; text-align: center;}
    .modal-overlay.is-visible .modal-box { transform: scale(1); }
    .modal-box h2 { font-size: 1.5rem; margin-bottom: 20px; color: var(--text-highlight); text-shadow: 2px 2px #000; }
    .modal-box p { font-size: 1rem; line-height: 1.6; min-height: 3em; }
    .btn { background: var(--ui-main); color: var(--text-light); border: 4px solid var(--ui-border-dark); box-shadow: inset 0 0 0 4px var(--ui-border-light); padding: 15px 35px; text-decoration: none; font-size: 1.2rem; transition: all 0.1s; cursor: var(--cursor-pointer); font-family: 'Press Start 2P', cursive; text-transform: uppercase; outline: none; }
    .btn:hover:not(:disabled) { background: var(--ui-border-light); color: var(--bg-dark); }
    .btn:focus-visible { background: var(--ui-border-light); color: var(--bg-dark); box-shadow: inset 0 0 0 4px var(--ui-border-light), 0 0 0 4px var(--text-highlight); }
    .modal-box .btn { margin-top: 30px; display: inline-block; }

    @keyframes damagePopup { 0% { transform: translate(-50%, 0); opacity: 1; } 100% { transform: translate(-50%, -80px); opacity: 0; } }
    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 50% { transform: translateX(5px); } 75% { transform: translateX(-5px); } }
    @keyframes flashRed { 50% { filter: brightness(3); } }
    @keyframes stage-intro { 0% { opacity: 0; transform: scale(0.5); } 70% { opacity: 1; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }
    @keyframes pulse-blessing { 50% { filter: brightness(1.5); } }
    @keyframes blink { 50% { opacity: 0.5; } }
    @media (max-width: 768px) { .combatants-area { flex-direction: column; align-items: center; gap: 20px; } .combatant-card { width: 100%; max-width: 400px; } }
</style>
</head>
<body>

<div id="story-intro" tabindex="0"> <p id="story-text"></p>
    <h1 id="stage-title">FASE FINAL</h1>
    <div id="introPrompt" class="intro-prompt" style="display: none;">PRESSIONE ENTER</div>
</div>

<div class="battle-screen">
    <div class="combatants-area">
        <div class="combatant-card" id="playerCard">
            <div class="status-effects" id="playerStatus"></div> <h2 id="playerName"></h2>
            <img src="{{ asset($character->avatar) }}" class="combatant-avatar" alt="Avatar do Jogador">
            <div class="stat-bar"><div class="stat-bar-fill hp" id="playerHpBar"></div><div class="stat-bar-text" id="playerHpText" aria-live="polite"></div></div>
            <div class="stat-bar"><div class="stat-bar-fill mp" id="playerMpBar"></div><div class="stat-bar-text" id="playerMpText" aria-live="polite"></div></div>
            <div class="stat-bar"><div class="stat-bar-fill xp" id="playerXpBar"></div><div class="stat-bar-text" id="playerXpText" aria-live="polite"></div></div>
            <div class="player-inventory">
                <div class="inventory-item">OURO: <span id="playerGold" aria-live="polite"></span></div>
                <div class="inventory-item">POÇÕES: <span id="playerPotions" aria-live="polite"></span></div>
            </div>
            <div class="actions-grid" id="actionsGrid"></div>
        </div>
        <div class="combatant-card" id="enemyCard">
            <div class="status-effects" id="enemyStatus"></div>
            <h2 id="enemyName"></h2>
            <img src="" class="combatant-avatar" id="enemyAvatar" alt="Avatar do Inimigo">
            <div class="stat-bar"><div class="stat-bar-fill hp" id="enemyHpBar"></div><div class="stat-bar-text" id="enemyHpText" aria-live="polite"></div></div>
        </div>
    </div>
    <div class="battle-log" id="battleLog" role="log" aria-live="polite"></div>
</div>

<div class="modal-overlay" id="endgameModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-box">
        <h2 id="modalTitle">TÍTULO</h2>
        <p id="modalText">Mensagem...</p>
        <a href="{{ route('home') }}" class="btn" id="modalLink">AÇÃO</a>
    </div>
</div>

<script>
// --- Novo: Gerenciador de Áudio ---
const AudioManager = {
    sounds: {
        // 'attack': new Audio('path/to/attack.wav'),
        // 'heal': new Audio('path/to/heal.wav'),
        // 'crit': new Audio('path/to/crit.wav'),
        // 'levelUp': new Audio('path/to/levelUp.wav'),
        // 'enemyAttack': new Audio('path/to/enemyAttack.wav'),
        // 'bossSpecial': new Audio('path/to/bossSpecial.wav'), // Para Stomp, Meteor, VoidRift
        // 'playerHit': new Audio('path/to/playerHit.wav'),
        // 'victory': new Audio('path/to/finalVictory.wav'), // Som de vitória diferente!
        // 'defeat': new Audio('path/to/defeat.wav'),
        // 'blessing': new Audio('path/to/blessing.wav'), // Som da bênção
    },
    play(key) {
        if (this.sounds[key]) {
            this.sounds[key].currentTime = 0;
            this.sounds[key].play();
        }
    }
};

// --- LÓGICA DA INTRODUÇÃO (Melhorada com skip) ---
const storyText = `APÓS SOBREVIVER ÀS TERRAS SOMBRIAS, {{ $character->name }} CHEGA AO COVIL DO MAL SUPREMO. O AR CREPITA COM PODER PROFANO. ESTA É A BATALHA FINAL...`;

const Intro = {
    storyContainer: document.getElementById('story-intro'),
    storyTextEl: document.getElementById('story-text'),
    stageTitleEl: document.getElementById('stage-title'),
    promptEl: document.getElementById('introPrompt'),
    battleScreenEl: document.querySelector('.battle-screen'),
    isTyping: false,
    timeoutID: null,
    
    start() {
        this.storyContainer.focus();
        this.bindEvents();
        this.typewriter(storyText);
    },
    bindEvents() {
        this.storyContainer.addEventListener('click', () => this.handleAdvance());
        this.storyContainer.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') this.handleAdvance();
        });
    },
    handleAdvance() {
        if (this.isTyping) {
            this.skipTyping();
        }
    },
    skipTyping() {
        clearTimeout(this.timeoutID);
        this.isTyping = false;
        this.storyTextEl.innerHTML = storyText;
        this.promptEl.style.display = 'none';
        setTimeout(() => this.showStageTitle(), 1000);
    },
    typewriter(text, i = 0) {
        this.isTyping = true;
        if (i < text.length) {
            this.storyTextEl.innerHTML += text.charAt(i);
            this.timeoutID = setTimeout(() => this.typewriter(text, i + 1), 50);
        } else {
            this.isTyping = false;
            this.promptEl.style.display = 'none';
            setTimeout(() => this.showStageTitle(), 2000);
        }
    },
    showStageTitle() {
        this.stageTitleEl.classList.add('visible');
        setTimeout(() => this.hideIntro(), 2500);
    },
    hideIntro() {
        this.storyContainer.style.opacity = '0';
        this.storyContainer.addEventListener('transitionend', () => {
            this.storyContainer.remove();
            this.showBattleScreen();
        }, { once: true });
    },
    showBattleScreen() {
        this.battleScreenEl.classList.add('visible');
        Game.init();
    }
};

// --- LÓGICA PRINCIPAL DO JOGO ---
const Game = {
    state: {
        player: {
            name: "{{ $character->name }}",
            // Bug Crítico Corrigido: Usando 'max_hp' e 'max_mp' e convertendo para NÚMERO
            hp: {{ $character->hp ?? 100 }},
            maxHp: {{ $character->max_hp ?? 100 }},
            mp: {{ $character->mp ?? 50 }},
            maxMp: {{ $character->max_mp ?? 50 }},
            attack: {{ $character->attack ?? 10 }},
            defense: {{ $character->defense ?? 5 }},
            sp_attack: {{ $character->special_attack ?? 10 }},
            sp_defense: {{ $character->special_defense ?? 5 }},
            speed: {{ $character->speed ?? 10 }},
            level: {{ $character->level ?? 1 }},
            xp: {{ $character->exp ?? 0 }},
            gold: {{ $character->gold ?? 0 }},
            potions: {{ $character->potions ?? 3 }},
            xpToNextLevel: 999999, // Será recalculado
            statusEffects: {},
            blessingReceived: false // Controla a bênção
        },
        enemy: {},
        enemies: [
            { name:"Behemoth Ancestral", hp: 600, attack: 25, defense: 60, sp_defense: 40, xp: 1000, gold: 500, specialChance: 0.3, specialMoves: [{ type: 'stomp', power: 1.6 }], img: "{{ asset('img/behemot.png') }}" },
            { name:"Arquimaga do Caos", hp: 150, attack: 50, defense: 40, sp_defense: 60, xp: 1500, gold: 750, specialChance: 0.5, specialMoves: [{ type: 'meteor', sp_attack: 120 }], img: "{{ asset('img/maga-sombria.png') }}" },
            { name:"Rei do Vazio", hp: 300, attack: 60, defense: 70, sp_defense: 50, xp: 0, gold: 0, specialChance: 0.4, specialMoves: [{ type: 'voidRift', power: 2.5, drain: 50 }], img: "{{ asset('img/rei-do-vazio.png') }}" }
        ],
        currentEnemyIndex: 0,
        gameState: 'PLAYER_TURN',
    },
    
    elements: {
        player: { card: document.getElementById('playerCard'), name: document.getElementById('playerName'), hpBar: document.getElementById('playerHpBar'), hpText: document.getElementById('playerHpText'), mpBar: document.getElementById('playerMpBar'), mpText: document.getElementById('playerMpText'), xpBar: document.getElementById('playerXpBar'), xpText: document.getElementById('playerXpText'), actions: document.getElementById('actionsGrid'), goldDisplay: document.getElementById('playerGold'), potionsDisplay: document.getElementById('playerPotions'), status: document.getElementById('playerStatus') },
        enemy: { card: document.getElementById('enemyCard'), name: document.getElementById('enemyName'), hpBar: document.getElementById('enemyHpBar'), hpText: document.getElementById('enemyHpText'), avatar: document.getElementById('enemyAvatar'), status: document.getElementById('enemyStatus') },
        log: document.getElementById('battleLog'),
        modal: { container: document.getElementById('endgameModal'), title: document.getElementById('modalTitle'), text: document.getElementById('modalText'), link: document.getElementById('modalLink') }
    },

    actions: {
        attack: { name: 'ATAQUE', cost: 0, type: 'mp', target: 'enemy', basePower: 1, stat: 'attack' },
        skill: { name: 'MAGIA', cost: 15, type: 'mp', target: 'enemy', basePower: 1.5, stat: 'sp_attack' },
        ultimate: { name: 'ESPECIAL', cost: 30, type: 'mp', target: 'enemy', basePower: 2.5, stat: 'sp_attack' },
        potion: { name: 'POÇÃO', cost: 1, type: 'potion', target: 'player', basePower: 100, stat: 'heal' },
    },

    // ===== FUNÇÃO DE INICIALIZAÇÃO =====
    init() {
        this.parseStats(); // Substitui sanitizeStats
        this.loadEnemy(); 
        this.renderActionButtons(); 
        this.updateUI();
        this.logMessage(`O MAL SE REVELA: ${this.state.enemy.name.toUpperCase()}!`, 'log-system');
    },

    parseStats() {
        const p = this.state.player;
        // Bug Corrigido: O XP para o próximo nível era 9999. Agora é dinâmico.
        p.xpToNextLevel = Math.floor(50 * Math.pow(p.level, 1.8)); // Torna o level up mais difícil
        
        // Garante que a vida/mp atual não ultrapasse a máxima ao carregar
        p.hp = Math.min(p.hp, p.maxHp);
        p.mp = Math.min(p.mp, p.maxMp);
        
        // Zera o XP para a barra (já que é a fase final, mas permite level up se ganhar)
        if (p.level >= 10) { // Exemplo de "level cap"
             p.xp = 0;
             p.xpToNextLevel = 999999;
        }
    },

    calculateDamage(power, defense) {
        const effectiveDefense = (defense || 0) * 0.5;
        const baseDamage = Math.max(1, power - effectiveDefense);
        return Math.floor(baseDamage * (Math.random() * 0.4 + 0.8)); // 80%-120%
    },
    
    // ===== TURNO DO JOGADOR =====
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
            AudioManager.play('heal');
        } else {
            const enemyDefense = (action.stat === 'attack') ? this.state.enemy.defense : (this.state.enemy.sp_defense || 0);
            const power = this.state.player[action.stat] * action.basePower;
            let damage = this.calculateDamage(power, enemyDefense);
            
            let isCrit = false;
            if (Math.random() < 0.2) { // 20% crit
                damage = Math.floor(damage * 1.5);
                isCrit = true;
                this.logMessage('ACERTO CRÍTICO!', 'log-crit');
                this.shake(this.elements.enemy.card);
                AudioManager.play('crit');
            } else {
                 AudioManager.play('attack');
            }
            
            this.state.enemy.hp -= damage;
            this.flash(this.elements.enemy.card, 'flash-red');
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

    // ===== TURNO DO INIMIGO =====
    enemyTurn() {
        this.setGameState('ENEMY_TURN');
        const enemy = this.state.enemy;
        let damage = 0;
        
        if (enemy.specialMoves && Math.random() < enemy.specialChance) {
            const move = enemy.specialMoves[0];
            AudioManager.play('bossSpecial');
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
                    enemy.hp = Math.min(enemy.maxHp, enemy.hp + drained); // Inimigo se cura
                    this.state.player.mp = Math.max(0, this.state.player.mp - move.drain); // Drena MP
                    this.logMessage(`SUA VIDA E MANA SÃO DRENADAS!`, 'log-drain');
                    break;
            }
        } else {
            damage = this.calculateDamage(enemy.attack, this.state.player.defense);
            this.logMessage(`${enemy.name.toUpperCase()} ATACA!`, 'log-enemy');
            AudioManager.play('enemyAttack');
        }
        
        if (damage > 0) {
            this.state.player.hp -= damage;
            this.flash(this.elements.player.card, 'flash-red');
            this.shake(this.elements.player.card);
            this.showPopup(damage, this.elements.player.card, false);
            AudioManager.play('playerHit');
        }

        // Bug Corrigido: Adiciona regen de MP e processamento de status
        this.processStatusEffects();
        this.state.player.mp = Math.min(this.state.player.maxMp, this.state.player.mp + 5);
        
        this.updateUI();
        if(this.state.player.hp <= 0) { this.logMessage('VOCÊ FOI DERROTADO...', 'log-system'); this.gameOver(false); return; }
        
        setTimeout(() => this.setGameState('PLAYER_TURN'), 1000);
    },
    
    // ===== LÓGICAS DE ESTADO (Melhoradas) =====
    processStatusEffects() {
        // (Nenhum inimigo desta fase aplica status, mas a função está pronta)
        this.updateStatusIcons();
    },

    gainXP(amount) {
        if(amount <= 0 || this.state.player.xpToNextLevel >= 999999) return; // Não ganha XP se for cap
        this.logMessage(`+${amount} XP!`, 'log-crit'); this.state.player.xp += amount;
        
        while (this.state.player.xp >= this.state.player.xpToNextLevel) {
            this.state.player.xp -= this.state.player.xpToNextLevel; this.state.player.level++;
            this.state.player.xpToNextLevel = Math.floor(50 * Math.pow(this.state.player.level, 1.8));
            this.state.player.maxHp += 25; this.state.player.maxMp += 20;
            this.state.player.attack += 7; this.state.player.defense += 5; this.state.player.sp_attack += 6;
            const healAmount = Math.floor(this.state.player.maxHp * 0.5);
            this.state.player.hp = Math.min(this.state.player.maxHp, this.state.player.hp + healAmount);
            this.state.player.mp = this.state.player.maxMp;
            this.logMessage(`LEVEL UP! NÍVEL ${this.state.player.level}!`, 'log-lvlup');
            AudioManager.play('levelUp');
        }
        this.updateUI();
    },

    nextEnemy() {
        this.state.currentEnemyIndex++;
        
        // Mecânica da Bênção (agora usa 'blessingReceived')
        if (this.state.currentEnemyIndex === 1 && !this.state.player.blessingReceived) {
            this.state.player.blessingReceived = true;
            this.logMessage('UMA ENERGIA ANCESTRAL FLUI POR SUAS VEIAS!', 'log-blessing');
            this.logMessage('BÊNÇÃO DIVINA: SEUS PODERES AUMENTARAM!', 'log-blessing');
            AudioManager.play('blessing');
            
            this.state.player.attack = Math.floor(this.state.player.attack * 1.5);
            this.state.player.defense = Math.floor(this.state.player.defense * 1.5);
            this.state.player.sp_attack = Math.floor(this.state.player.sp_attack * 1.5);
            this.state.player.maxHp = Math.floor(this.state.player.maxHp * 1.5);
            
            const healAmount = this.state.player.maxHp; // Cura completa
            this.state.player.hp = Math.min(this.state.player.maxHp, this.state.player.hp + healAmount);
            this.state.player.mp = this.state.player.maxMp;
        }
        
        if (this.state.currentEnemyIndex >= this.state.enemies.length) { this.gameOver(true); return; }
        
        this.loadEnemy();
        this.updateUI();
        this.logMessage(`DAS PROFUNDEZAS, EMERGE ${this.state.enemy.name.toUpperCase()}!`, 'log-system');
        this.setGameState('PLAYER_TURN');
    },
    
    loadEnemy() { 
        this.state.enemy = { ...this.state.enemies[this.state.currentEnemyIndex] }; 
        this.state.enemy.maxHp = this.state.enemy.hp; 
        // Bug Corrigido: Atualiza a imagem
        this.elements.enemy.avatar.src = this.state.enemy.img; 
    },
    
    // Bug Corrigido: Função de game over agora salva o progresso na vitória
    async gameOver(isVictory) {
        this.setGameState('GAME_OVER');
        if (isVictory) {
            AudioManager.play('victory');
            this.logMessage('VITÓRIA LENDÁRIA!', 'log-lvlup');
            try {
                await this.saveProgress(); // Salva o estado final
                this.logMessage('PROGRESSO FINAL SALVO!', 'log-system');
                
                // Mostra o modal de vitória e inicia a digitação da msg secreta
                this.showModal(
                    'VITÓRIA LENDÁRIA',
                    'O segredo das trevas foi revelado...',
                    "{{ route('home') }}",
                    'JOGAR NOVAMENTE'
                );
                
                setTimeout(() => this.typeSecretMessage(), 2000);

            } catch (error) {
                this.showModal('ERRO DE CONEXÃO', 'Não foi possível salvar seu progresso.', window.location.href, 'TENTAR NOVAMENTE');
            }
        } else {
            AudioManager.play('defeat');
            this.showModal('FIM DE JOGO', 'AS TREVAS CONSUMIRAM O REINO...', "{{ route('home') }}", 'REINICIAR');
        }
    },

    typeSecretMessage() {
        const secretMessage = "PROGRAMAÇÃO NÃO É MÁGICA!";
        const textElement = this.elements.modal.text;
        let i = 0;
        textElement.textContent = "O SEGREDO É: ";
        const typingInterval = setInterval(() => {
            if (i < secretMessage.length) { textElement.textContent += secretMessage.charAt(i); i++; }
            else { clearInterval(typingInterval); }
        }, 150);
    },
    
    async saveProgress() {
        const playerData = this.state.player;
        try { 
            const response = await fetch("{{ route('character.saveProgress', $character->id) }}", { 
                method: 'POST', 
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, 
                body: JSON.stringify(playerData) 
            });
            if (!response.ok) throw new Error('Falha no servidor');
            return await response.json();
        } catch (error) { 
            console.error('Erro ao salvar o progresso:', error); 
            throw error;
        }
    },

    // ===== FUNÇÕES DE UI (HELPERS) =====
    updateUI() {
        const { player, enemy } = this.state;
        const { player: playerEl, enemy: enemyEl } = this.elements;
        
        playerEl.name.textContent = `${player.name} LV ${player.level}`;
        playerEl.hpBar.style.width = `${Math.max(0, player.hp / player.maxHp * 100)}%`; 
        playerEl.hpText.textContent = `HP: ${Math.max(0, Math.ceil(player.hp))}/${player.maxHp}`;
        playerEl.mpBar.style.width = `${Math.max(0, player.mp / player.maxMp * 100)}%`; 
        playerEl.mpText.textContent = `MP: ${Math.max(0, Math.ceil(player.mp))}/${player.maxMp}`;
        playerEl.xpBar.style.width = `${Math.max(0, player.xp / player.xpToNextLevel * 100)}%`; 
        playerEl.xpText.textContent = (player.xpToNextLevel >= 999999) ? 'XP: MAX' : `XP: ${player.xp}/${player.xpToNextLevel}`;
        playerEl.goldDisplay.textContent = player.gold;
        playerEl.potionsDisplay.textContent = player.potions;
        
        enemyEl.name.textContent = enemy.name.toUpperCase(); 
        enemyEl.avatar.src = enemy.img; 
        enemyEl.hpBar.style.width = `${Math.max(0, enemy.hp / enemy.maxHp * 100)}%`; 
        enemyEl.hpText.textContent = `HP: ${Math.max(0, Math.ceil(enemy.hp))}/${enemy.maxHp}`;
        
        this.updateStatusIcons();
        this.updateActionButtons();
    },

    updateActionButtons() {
        const { player, gameState } = this.state;
        this.elements.player.actions.querySelectorAll('button').forEach(btn => {
            const actionKey = btn.dataset.actionKey;
            const action = this.actions[actionKey];
            
            let disabled = (gameState !== 'PLAYER_TURN');
            if (action.type === 'mp' && player.mp < action.cost) disabled = true;
            if (action.type === 'potion' && player.potions < action.cost) disabled = true;
            
            btn.disabled = disabled;
            
            if (action.type === 'potion') {
                btn.innerHTML = `POÇÃO (${player.potions})`;
            }
        });
    },

    updateStatusIcons() {
        const playerStatusEl = this.elements.player.status;
        playerStatusEl.innerHTML = ''; // Limpa
        if (this.state.player.blessingReceived) {
            playerStatusEl.innerHTML += `<div class="status-icon blessing" title="Abençoado">★</div>`;
        }
        // Adicione outros status aqui (ex: voidRift)
    },
    
    renderActionButtons() {
        this.elements.player.actions.innerHTML = '';
        for (const key in this.actions) { 
            const action = this.actions[key]; 
            const btn = document.createElement('button'); 
            btn.className = 'action-btn'; 
            btn.dataset.actionKey = key; 
            btn.innerHTML = action.name; 
            btn.onclick = () => this.executeTurn(key); 
            if (action.type === 'mp' && action.cost > 0) {
                btn.setAttribute('aria-label', `${action.name} (${action.cost} MP)`);
            } else {
                btn.setAttribute('aria-label', action.name);
            }
            this.elements.player.actions.appendChild(btn); 
        }
    },
    
    logMessage(message, className = '') { const p = document.createElement('p'); p.innerHTML = message; if (className) p.className = className; this.elements.log.appendChild(p); this.elements.log.scrollTop = this.elements.log.scrollHeight; },
    showPopup(text, targetCard, isHeal = false, isCrit = false) { const popup = document.createElement('div'); popup.className = 'damage-popup'; popup.textContent = text; if (isHeal) popup.classList.add('heal'); if (isCrit) popup.classList.add('crit'); targetCard.appendChild(popup); setTimeout(() => popup.remove(), 1000); },
    shake(element) { element.classList.add('shake'); setTimeout(() => element.classList.remove('shake'), 400); },
    flash(element, className) { element.classList.add(className); setTimeout(() => element.classList.remove(className), 200); },

    // Função de Modal Genérica (A11y e Reutilizável)
    showModal(title, text, linkUrl, linkText) {
        this.elements.modal.title.textContent = title;
        this.elements.modal.text.innerHTML = text;
        this.elements.modal.link.href = linkUrl;
        this.elements.modal.link.textContent = linkText;
        this.elements.modal.container.classList.add('is-visible');
    },

    setGameState(newState) { 
        this.state.gameState = newState; 
        this.updateActionButtons();
    }
};

document.addEventListener('DOMContentLoaded', () => Intro.start());
</script>
</body>
</html>