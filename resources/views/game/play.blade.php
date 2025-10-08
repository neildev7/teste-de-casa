<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>RPG - {{ $character->name }}</title>
<style>
body { font-family: monospace; background:#111; color:#0f0; padding:20px; display:flex; flex-direction:column; align-items:center;}
h1 { text-align:center; }
.card { display:inline-block; background:#000; padding:20px; border-radius:10px; border:1px solid #0f0; margin:10px; width:250px; text-align:center; transition: transform 0.3s; }
.card:hover { transform: scale(1.03); }
.avatar { width:100px; border-radius:10px; }
.bar-container { background:#222; width:100%; height:14px; border-radius:10px; margin:5px 0; }
.bar { height:100%; border-radius:10px; transition: width 0.3s; }
.hp { background:#0f0; }
.mp { background:#0ff; }
.actions { margin-top:10px; }
button { background:#0f0; color:#111; border:none; padding:10px; margin:5px; cursor:pointer; font-weight:bold; border-radius:5px; }
button:hover { background:#9f9; }
.console { background:#000; padding:15px; border:1px solid #0f0; height:200px; overflow-y:auto; margin-top:20px; width:520px; }
.highlight { color:#ff0; font-weight:bold; }
</style>
</head>
<body>

<h1>👾 {{ $character->name }} entra na aventura!</h1>

<div class="card">
  <img src="{{ asset($character->avatar) }}" class="avatar" alt="avatar">
  <h2 id="playerName">{{ $character->name }} (Lv {{ $character->level }})</h2>
  <div class="bar-container"><div class="bar hp" id="playerHpBar" style="width:100%"></div></div>
  <div class="bar-container"><div class="bar mp" id="playerMpBar" style="width:100%"></div></div>
  <p id="playerStats">HP: {{ $character->hp }} | MP: {{ $character->mp }} | Poções: 3 | XP: 0 | Lv: {{ $character->level }}</p>
  <div class="actions">
    <button id="attackBtn">Ataque ⚔️</button>
    <button id="skillBtn">Habilidade ✨ (10 MP)</button>
    <button id="ultimateBtn">Ultimate 🔥 (20 MP)</button>
    <button id="itemBtn">Poção ❤️</button>
  </div>
</div>

<div class="card" id="enemyCard">
  <img src="{{ asset('img/goblin.png') }}" class="avatar" alt="Inimigo">
  <h2 id="enemyName">Goblin</h2>
  <div class="bar-container"><div class="bar hp" id="enemyHpBar" style="width:100%"></div></div>
  <p id="enemyStats">HP: 30</p>
</div>

<div class="console" id="console"><p>Um inimigo aparece!</p></div>

<script>
const consoleBox = document.getElementById('console');
const attackBtn = document.getElementById('attackBtn');
const skillBtn = document.getElementById('skillBtn');
const ultimateBtn = document.getElementById('ultimateBtn');
const itemBtn = document.getElementById('itemBtn');

const playerHpBar = document.getElementById('playerHpBar');
const playerMpBar = document.getElementById('playerMpBar');
const playerStats = document.getElementById('playerStats');
const playerName = document.getElementById('playerName');
const enemyCard = document.getElementById('enemyCard');
const enemyHpBar = document.getElementById('enemyHpBar');
const enemyName = document.getElementById('enemyName');
const enemyStats = document.getElementById('enemyStats');

let player = {
    hp: {{ $character->hp }}, maxHp: {{ $character->hp }},
    mp: {{ $character->mp }}, maxMp: {{ $character->mp }},
    attack: {{ $character->attack }}, defense: {{ $character->defense }},
    potions: 3, level: {{ $character->level }}, xp:0, xpNext:50
};

const phases = [
    { name:"Goblin", hp:30, attack:8, defense:3, img:"{{ asset('img/goblin.png') }}", gold:5 },
    { name:"Orc", hp:50, attack:12, defense:6, img:"{{ asset('img/orc.png') }}", gold:10 },
    { name:"Dragão", hp:80, attack:18, defense:10, img:"{{ asset('img/dragao.png') }}", gold:20 }
];

let phaseIndex=0;
let enemy = {...phases[phaseIndex]};

// Funções utilitárias
function log(msg, cls="") { consoleBox.innerHTML += `<p class="${cls}">${msg}</p>`; consoleBox.scrollTop = consoleBox.scrollHeight; }
function updateUI() {
    playerHpBar.style.width = Math.max(0,(player.hp/player.maxHp)*100)+"%";
    playerMpBar.style.width = Math.max(0,(player.mp/player.maxMp)*100)+"%";
    playerStats.innerText = `HP: ${Math.max(0,player.hp)} | MP: ${Math.max(0,player.mp)} | Poções: ${player.potions} | XP: ${player.xp}/${player.xpNext} | Lv: ${player.level}`;
    enemyName.innerText = enemy.name;
    enemyHpBar.style.width = Math.max(0,(enemy.hp/phases[phaseIndex].hp)*100)+"%";
    enemyStats.innerText = `HP: ${Math.max(0,enemy.hp)}`;
}

function calculateDamage(base, def) {
    let crit = Math.random()<0.15; let dmg=Math.max(1, base-def+Math.floor(Math.random()*5)-2);
    if(crit) { dmg*=2; log("✨ Acerto crítico!","highlight"); }
    return dmg;
}

function gainXP(amount){
    player.xp += amount;
    log(`🎉 Ganhou ${amount} XP!`);
    while(player.xp >= player.xpNext){
        player.xp -= player.xpNext;
        player.level++;
        let gain=20;
        player.maxHp+=gain; player.hp=player.maxHp;
        player.maxMp+=gain; player.mp=player.maxMp;
        player.attack+=gain; player.defense+=gain;
        player.xpNext = Math.floor(player.xpNext*1.5);
        log(`🎆 Subiu para Lv ${player.level}! Todos os stats aumentaram +${gain}`,"highlight");
    }
}

// Turno inimigo
function enemyTurn(){
    if(enemy.hp<=0){ gainXP(enemy.hp*0+20); loot(); nextEnemy(); return; }
    let dmg = calculateDamage(enemy.attack,player.defense);
    player.hp-=dmg; log(`${enemy.name} atacou causando ${dmg} de dano!`);
    if(player.hp<=0){ log("💀 Você foi derrotado! Game Over!","highlight"); disableAll(); }
    player.mp+=2; if(player.mp>player.maxMp) player.mp=player.maxMp;
    updateUI();
}

// Próximo inimigo
function nextEnemy(){
    phaseIndex++;
    if(phaseIndex>=phases.length){ log("🏆 Todos os inimigos derrotados! Vitória final!","highlight"); disableAll(); return; }
    enemy={...phases[phaseIndex]};
    enemyCard.querySelector('img').src=enemy.img;
    log(`🚨 Novo inimigo: ${enemy.name}!`,"highlight");
    updateUI();
}

// Loot automático
function loot(){ 
    let pot = Math.random()<0.5?1:0; 
    player.potions+=pot; 
    if(pot>0) log(`🧪 Você encontrou 1 poção!`); 
}

// Ações do jogador
function disableAll(){ attackBtn.disabled=true; skillBtn.disabled=true; ultimateBtn.disabled=true; itemBtn.disabled=true; }
attackBtn.onclick=()=>{ if(player.hp<=0||enemy.hp<=0)return; enemy.hp-=calculateDamage(player.attack,enemy.defense); log("Você atacou!"); enemyTurn(); };
skillBtn.onclick=()=>{ if(player.hp<=0||enemy.hp<=0)return; if(player.mp<10){log("❌ MP insuficiente!");return;} player.mp-=10; enemy.hp-=calculateDamage(player.attack+5,enemy.defense); log("Você usou Habilidade!"); enemyTurn(); };
ultimateBtn.onclick=()=>{ if(player.hp<=0||enemy.hp<=0)return; if(player.mp<20){log("❌ MP insuficiente!");return;} player.mp-=20; enemy.hp-=calculateDamage(player.attack+15,enemy.defense); log("🔥 Ultimate usada!"); enemyTurn(); };
itemBtn.onclick=()=>{ if(player.potions<=0){log("❌ Sem poções!");return;} player.potions--; let heal=30; player.hp+=heal;if(player.hp>player.maxHp)player.hp=player.maxHp; log(`🧪 Poção usada! Recuperou ${heal} HP!`); enemyTurn(); };

// Inicializa
updateUI();
</script>
</body>
</html>
