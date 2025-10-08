<!DOCTYPE html>
<html lang="pt-br" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Last SENAI | Guild Hall</title>

    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --paper-color: #f7f3e8; --wood-color: #5d4037; --metal-color: #a9a9a9;
            --gold-color: #ffd700; --gold-dark-color: #eab308; --danger-color: #a02c2c;
            --success-color: #2e7d32; --text-dark: #333333;
            --transition-speed-fast: 0.3s; --transition-speed-slow: 0.6s;
        }

        *, *::before, *::after { box-sizing: border-box; }
        .no-js body { visibility: hidden; }

        body {
            margin: 0; padding: 20px; font-family: 'Cinzel', serif;
            background: var(--paper-color) url("{{ asset('img/giphy.gif') }}") no-repeat center center fixed;
            background-size: cover; color: var(--text-dark); text-align: center;
            min-height: 100vh; display: flex; justify-content: center; align-items: center;
            background-blend-mode: multiply; overflow-x: hidden;
        }
        
        /* CAMADAS GLOBAIS: PRELOADER, OVERLAYS, MODAIS */
        .preloader, .modal-overlay {
            position: fixed; inset: 0; z-index: 100;
            display: flex; justify-content: center; align-items: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        .preloader { background-color: var(--wood-color); }
        .preloader.is-hidden { opacity: 0; visibility: hidden; }
        .preloader__spinner {
            width: 60px; height: 60px; border: 5px solid rgba(255, 215, 0, 0.3);
            border-top-color: var(--gold-color); border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.7); z-index: 0; }
        
        /* ESTILOS DOS MODAIS */
        .modal-overlay {
            background: rgba(0,0,0,0.8); opacity: 0; visibility: hidden; z-index: 102;
        }
        .modal-overlay.is-visible { opacity: 1; visibility: visible; }
        .modal-box {
            background-color: var(--paper-color); border: 10px solid;
            border-image: linear-gradient(45deg, #4a3227, #8b6b5c) 1;
            padding: 30px; max-width: 500px; width: 90%;
            color: var(--text-dark); text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.7);
            transform: scale(0.9); transition: transform 0.3s ease;
        }
        .modal-overlay.is-visible .modal-box { transform: scale(1); }
        .modal-box h3 { font-family: 'IM Fell English SC', serif; color: var(--wood-color); margin: 0 0 20px; }
        .modal-input {
            width: 100%; padding: 10px; font-family: 'Segoe UI', sans-serif;
            border: 2px solid var(--wood-color); background: #fff;
            margin-bottom: 20px; font-size: 1rem;
        }
        .modal-actions { display: flex; justify-content: center; gap: 15px; }
        .modal-message { margin-top: 15px; font-weight: bold; min-height: 1.2em; }
        .modal-message.success { color: var(--success-color); }
        .modal-message.error { color: var(--danger-color); }
        
        main.main-container {
            position: relative; z-index: 1; padding: clamp(30px, 5vw, 50px);
            max-width: 900px; width: 95%; background-color: var(--wood-color);
            border: 15px solid; border-image: linear-gradient(45deg, #4a3227, #8b6b5c) 1;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.8), inset 0 0 20px rgba(0,0,0,0.3);
            color: var(--paper-color);
        }

        h1 {
            font-family: 'IM Fell English SC', serif; font-size: clamp(2.5rem, 6vw, 4rem);
            color: var(--gold-color); text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.9);
            margin: 0 0 30px; border-bottom: 3px double var(--gold-color);
            padding-bottom: 15px; display: inline-block;
        }

        .btn-group { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
        
        .btn {
            display: inline-block; background: linear-gradient(145deg, var(--metal-color), #8d8d8d);
            color: var(--wood-color); border: 3px outset #c0c0c0; padding: 12px 25px;
            text-decoration: none; font-weight: 900; font-size: 1rem;
            transition: all var(--transition-speed-fast) ease-in-out; cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5), inset 0 1px 2px rgba(255,255,255,0.3);
            letter-spacing: 1.5px; text-transform: uppercase; font-family: 'Cinzel', serif;
        }
        .btn:hover, .btn:focus-visible {
            background: linear-gradient(145deg, var(--gold-color), var(--gold-dark-color));
            color: var(--text-dark); border-style: inset; border-color: #fff8e1;
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.6), inset 0 1px 2px rgba(0,0,0,0.3);
            transform: translateY(-4px) scale(1.05); outline: none;
        }
        .btn.is-active {
            background: linear-gradient(145deg, var(--danger-color), #7f1d1d);
            color: white; border-color: #fca5a5;
        }
        .btn--danger { background: var(--danger-color); color: white; }
        .btn--secondary { background: var(--metal-color); }
        
        .character-list {
            display: grid; grid-template-rows: 0fr; opacity: 0; visibility: hidden;
            transition: all var(--transition-speed-slow) ease;
        }
        .character-list.is-visible { grid-template-rows: 1fr; opacity: 1; visibility: visible; margin-top: 40px; }
        .char-card-group {
            overflow: hidden; background: rgba(247, 243, 232, 0.9);
            border: 5px solid var(--wood-color); border-radius: 10px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
            color: var(--text-dark); padding: 30px;
            display: flex; flex-wrap: wrap; justify-content: center; gap: 25px;
        }
        
        .character-card {
            background: var(--paper-color); border: 2px solid var(--wood-color);
            border-radius: 8px; padding: 15px; width: 220px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            display: flex; flex-direction: column; justify-content: space-between;
            opacity: 0; transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .character-card.is-in-view { opacity: 1; transform: translateY(0); }
        .character-card.is-deleting { opacity: 0; transform: scale(0.8); }

        .character-card__avatar {
            width: 100px; height: 100px; border-radius: 50%; border: 3px solid var(--gold-color);
            margin: 0 auto 10px; object-fit: cover; background-color: var(--wood-color);
        }
        
        .char-name { font-weight: 700; color: var(--wood-color); font-family: 'Cinzel', serif; font-size: 1.2rem; word-wrap: break-word; }
        .char-meta { font-size: 0.75rem; color: #777; font-style: italic; }
        
        .card-actions {
            margin-top: 15px; display: flex; flex-direction: column; gap: 10px;
        }
        .action-btn {
            display: flex; align-items: center; justify-content: center;
            gap: 8px; padding: 8px 12px; font-size: 0.8rem;
            border-radius: 5px; border-width: 2px;
        }
        .action-btn svg { width: 16px; height: 16px; fill: currentColor; }
        .play-btn { background-color: var(--success-color); color: white; }
        .delete-btn { background-color: var(--danger-color); color: white; }
    </style>
</head>
<body>
    <div class="preloader" id="preloader"><div class="preloader__spinner"></div></div>
    <div class="overlay"></div>

    <main class="main-container" id="mainContainer">
        <h1>The Last SENAI</h1>
        <div class="btn-group">
            <a href="{{ route('character.create') }}" class="btn">Criar Novo Avatar</a>
            <button id="toggleListBtn" class="btn" aria-controls="characterList" aria-expanded="false"
                    data-open-text="Acessar Livro de Jogadores" data-close-text="Fechar Livro">
                Acessar Livro de Jogadores
            </button>
        </div>
        <div id="characterList" class="character-list">
            <div class="char-card-group" id="cardGroup">
                @if($characters->isEmpty())
                    <p class="empty-message">O LIVRO DE REGISTROS ESTÁ VAZIO.</p>
                @else
                    @foreach($characters as $char)
                        <div class="character-card" data-character-id="{{ $char->id }}" data-character-name="{{ $char->name }}">
                            <img src="{{ asset($char->avatar ?? 'img/default.png') }}" alt="Avatar de {{ $char->name }}" class="character-card__avatar">
                            <div>
                                <p class="char-name">{{ $char->name }}</p>
                                <p class="char-meta">CLASSE: Operário Nv. {{ $char->level }}</p>
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('character.play', $char->id) }}" class="btn action-btn play-btn">
                                    <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    Jogar
                                </a>
                                <button class="btn action-btn edit-btn">
                                    <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                    Editar
                                </button>
                                <button class="btn action-btn delete-btn">
                                    <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                    Deletar
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </main>
    
    <div class="modal-overlay" id="editModal">
        <div class="modal-box">
            <h3>Editar Nome do Campeão</h3>
            <input type="text" id="editNameInput" class="modal-input" placeholder="Novo nome do personagem">
            <div class="modal-actions">
                <button class="btn btn--secondary" data-close-modal>Cancelar</button>
                <button class="btn" id="saveEditBtn">Salvar</button>
            </div>
            <div class="modal-message" id="editMessage"></div>
        </div>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <h3>Confirmar Exclusão</h3>
            <p id="deleteModalText">Tem certeza que deseja deletar este campeão?</p>
            <div class="modal-actions">
                <button class="btn btn--secondary" data-close-modal>Cancelar</button>
                <button class="btn btn--danger" id="confirmDeleteBtn">Deletar</button>
            </div>
            <div class="modal-message" id="deleteMessage"></div>
        </div>
    </div>

    <script>
        document.documentElement.classList.remove('no-js');
        document.addEventListener('DOMContentLoaded', () => {
            const app = {
                ui: {
                    preloader: document.getElementById('preloader'),
                    toggleBtn: document.getElementById('toggleListBtn'),
                    characterList: document.getElementById('characterList'),
                    cardGroup: document.getElementById('cardGroup'),
                    editModal: {
                        overlay: document.getElementById('editModal'),
                        input: document.getElementById('editNameInput'),
                        saveBtn: document.getElementById('saveEditBtn'),
                        message: document.getElementById('editMessage'),
                    },
                    deleteModal: {
                        overlay: document.getElementById('deleteModal'),
                        text: document.getElementById('deleteModalText'),
                        confirmBtn: document.getElementById('confirmDeleteBtn'),
                        message: document.getElementById('deleteMessage'),
                    },
                },
                state: {
                    isListVisible: false,
                    cardsAnimated: false,
                    activeCard: null,
                },

                init() {
                    this.bindEvents();
                    this.hidePreloader();
                },

                hidePreloader() {
                    window.addEventListener('load', () => {
                        this.ui.preloader.classList.add('is-hidden');
                    });
                },

                bindEvents() {
                    if (this.ui.toggleBtn) {
                        this.ui.toggleBtn.addEventListener('click', () => this.handleToggleList());
                    }
                    
                    if (this.ui.cardGroup) {
                        this.ui.cardGroup.addEventListener('click', (e) => {
                            const editBtn = e.target.closest('.edit-btn');
                            const deleteBtn = e.target.closest('.delete-btn');
                            if (editBtn) this.openEditModal(editBtn.closest('.character-card'));
                            if (deleteBtn) this.openDeleteModal(deleteBtn.closest('.character-card'));
                        });
                    }
                    
                    this.ui.editModal.saveBtn.addEventListener('click', () => this.handleSaveEdit());
                    this.ui.deleteModal.confirmBtn.addEventListener('click', () => this.handleConfirmDelete());
                    
                    document.querySelectorAll('[data-close-modal]').forEach(btn => {
                        btn.addEventListener('click', () => {
                            this.closeModal(this.ui.editModal.overlay);
                            this.closeModal(this.ui.deleteModal.overlay);
                        });
                    });
                },

                handleToggleList() {
                    this.state.isListVisible = !this.state.isListVisible;
                    this.ui.characterList.classList.toggle('is-visible', this.state.isListVisible);
                    this.ui.toggleBtn.classList.toggle('is-active', this.state.isListVisible);
                    this.ui.toggleBtn.textContent = this.state.isListVisible ? this.ui.toggleBtn.dataset.closeText : this.ui.toggleBtn.dataset.openText;

                    if (this.state.isListVisible && !this.state.cardsAnimated) {
                        this.animateCards();
                        this.state.cardsAnimated = true;
                    }
                },

                animateCards() {
                    this.ui.cardGroup.querySelectorAll('.character-card').forEach((card, index) => {
                        setTimeout(() => card.classList.add('is-in-view'), 100 * index);
                    });
                },

                openModal(modal) { modal.classList.add('is-visible'); },
                closeModal(modal) { modal.classList.remove('is-visible'); },

                openEditModal(card) {
                    this.state.activeCard = card;
                    this.ui.editModal.input.value = card.dataset.characterName;
                    this.ui.editModal.message.textContent = '';
                    this.openModal(this.ui.editModal.overlay);
                    this.ui.editModal.input.focus();
                },

                openDeleteModal(card) {
                    this.state.activeCard = card;
                    const name = card.dataset.characterName;
                    this.ui.deleteModal.text.textContent = `Tem certeza que deseja deletar "${name}" permanentemente?`;
                    this.ui.deleteModal.message.textContent = '';
                    this.openModal(this.ui.deleteModal.overlay);
                },

                async handleSaveEdit() {
                    const id = this.state.activeCard.dataset.characterId;
                    const newName = this.ui.editModal.input.value.trim();
                    const messageEl = this.ui.editModal.message;

                    if (!newName) {
                        messageEl.textContent = 'O nome não pode ficar vazio.';
                        messageEl.className = 'modal-message error';
                        return;
                    }

                    try {
                        const response = await fetch(`/character/update/${id}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ name: newName })
                        });
                        
                        if (!response.ok) throw new Error('Falha na comunicação com o servidor.');

                        const data = await response.json();
                        if (data.success) {
                            const nameEl = this.state.activeCard.querySelector('.char-name');
                            nameEl.textContent = newName;
                            this.state.activeCard.dataset.characterName = newName;
                            messageEl.textContent = 'Nome atualizado com sucesso!';
                            messageEl.className = 'modal-message success';
                            setTimeout(() => this.closeModal(this.ui.editModal.overlay), 1500);
                        } else {
                            throw new Error(data.message || 'Erro ao atualizar no servidor.');
                        }
                    } catch (error) {
                        messageEl.textContent = `Erro: ${error.message}`;
                        messageEl.className = 'modal-message error';
                    }
                },

                async handleConfirmDelete() {
                    const id = this.state.activeCard.dataset.characterId;
                    const messageEl = this.ui.deleteModal.message;

                    try {
                        const response = await fetch(`/character/delete/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        
                        if (!response.ok) throw new Error('Falha na comunicação com o servidor.');

                        const data = await response.json();
                        if (data.success) {
                            messageEl.textContent = 'Personagem deletado.';
                            messageEl.className = 'modal-message success';
                            this.state.activeCard.classList.add('is-deleting');
                            setTimeout(() => {
                                this.state.activeCard.remove();
                                this.closeModal(this.ui.deleteModal.overlay);
                            }, 500);
                        } else {
                            throw new Error(data.message || 'Erro ao deletar no servidor.');
                        }
                    } catch (error) {
                        messageEl.textContent = `Erro: ${error.message}`;
                        messageEl.className = 'modal-message error';
                    }
                }
            };
            app.init();
        });
    </script>
</body>
</html>