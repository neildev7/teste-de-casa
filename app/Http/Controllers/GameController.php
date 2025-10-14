<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class GameController extends Controller
{
    /**
     * Mostra a tela de criação de personagem (index.blade.php).
     */
    public function create()
    {
        return view('game.index');
    }

    /**
     * Armazena um novo personagem no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:30',
            'avatar' => 'required',
        ]);

        $character = Character::create([
            'name' => $request->name,
            'avatar' => $request->avatar,
            'hp' => 100, 'mp' => 50, 'attack' => 10, 'defense' => 10,
            'speed' => 10, 'special_attack' => 10, 'special_defense' => 10,
            'level' => 1, 'exp' => 0, 'gold' => 50,
            'potions' => 3 // Personagem começa com 3 poções
        ]);

        return redirect()->route('character.tutorial', $character->id);
    }

    /**
     * Mostra a tela de tutorial.
     */
    public function tutorial($id)
    {
        $character = Character::findOrFail($id);
        return view('game.tutorial', compact('character'));
    }

    /**
     * Mostra a tela de alocação de pontos.
     */
    public function allocate($id)
    {
        $character = Character::findOrFail($id);
        return view('game.allocate', compact('character'));
    }

    /**
     * Armazena os atributos distribuídos, somando-os aos stats base.
     */
    public function allocateStore(Request $request, $id)
    {
        $character = Character::findOrFail($id);

        $request->validate([
            'hp' => 'required|integer|min:0',
            'mp' => 'required|integer|min:0',
            'attack' => 'required|integer|min:0',
            'defense' => 'required|integer|min:0',
            'speed' => 'required|integer|min:0',
            'special_attack' => 'required|integer|min:0',
            'special_defense' => 'required|integer|min:0',
        ]);

        $character->update([
            'hp' => 100 + $request->input('hp'),
            'mp' => 50 + $request->input('mp'),
            'attack' => 10 + $request->input('attack'),
            'defense' => 10 + $request->input('defense'),
            'speed' => 10 + $request->input('speed'),
            'special_attack' => 10 + $request->input('special_attack'),
            'special_defense' => 10 + $request->input('special_defense'),
        ]);
        
        return redirect()->route('character.play', $character->id);
    }

    /**
     * Mostra a tela da Fase 1.
     */
    public function play($id)
    {
        $character = Character::findOrFail($id);
        return view('game.play', compact('character'));
    }

    /**
     * Mostra a tela da Fase 2.
     */
    public function play2($id)
    {
        $character = Character::findOrFail($id);
        return view('game.play2', compact('character'));
    }

    /**
     * Mostra a tela da Fase 3.
     */
    public function play3($id)
    {
        $character = Character::findOrFail($id);
        return view('game.play3', compact('character'));
    }

    /**
     * Salva o progresso do personagem vindo do JavaScript.
     */
    public function saveProgress(Request $request, $id)
    {
        $character = Character::findOrFail($id);

        $character->update([
            'hp' => $request->input('maxHp'),
            'mp' => $request->input('maxMp'),
            'attack' => $request->input('attack'),
            'defense' => $request->input('defense'),
            'special_attack' => $request->input('sp_attack'),
            'special_defense' => $request->input('sp_defense'),
            'speed' => $request->input('speed'),
            'level' => $request->input('level'),
            'exp' => $request->input('xp'),
            'gold' => $request->input('gold'),
            'potions' => $request->input('potions'),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Mostra a tela da loja.
     */
    public function shop($id, Request $request)
    {
        $character = Character::findOrFail($id);
        $next_stage = $request->query('next_stage', 'play');
        return view('game.shop', compact('character', 'next_stage'));
    }

    /**
     * Processa a compra de um item da loja.
     */
    public function buyItem(Request $request, $id)
    {
        $character = Character::findOrFail($id);
        $itemId = $request->input('itemId');

        $items = [
            'potion' => ['name' => 'Poção de Vida', 'cost' => 50, 'effect' => ['potions' => 1]],
            'elixir' => ['name' => 'Elixir de Mana', 'cost' => 75, 'effect' => ['mp' => 50]],
            'sword1' => ['name' => 'Espada de Ferro', 'cost' => 150, 'effect' => ['attack' => 5]],
            'sword2' => ['name' => 'Lâmina de Aço Valiriano', 'cost' => 400, 'effect' => ['attack' => 12, 'special_attack' => 5]],
            'shield1' => ['name' => 'Escudo de Madeira', 'cost' => 120, 'effect' => ['defense' => 5]],
            'armor1' => ['name' => 'Peitoral de Placas', 'cost' => 500, 'effect' => ['defense' => 15, 'hp' => 20]],
            'amulet1' => ['name' => 'Amuleto de Mana', 'cost' => 100, 'effect' => ['mp' => 20]],
            'ring1' => ['name' => 'Anel do Poder', 'cost' => 1000, 'effect' => ['attack' => 5, 'defense' => 5, 'special_attack' => 5, 'special_defense' => 5, 'speed' => 5]],
        ];

        $item = $items[$itemId] ?? null;

        if (!$item) { return response()->json(['success' => false, 'message' => 'Item não encontrado!']); }
        if ($character->gold < $item['cost']) { return response()->json(['success' => false, 'message' => 'Ouro insuficiente!']); }

        $character->gold -= $item['cost'];

        foreach ($item['effect'] as $stat => $bonus) {
            $character->$stat += $bonus;
        }

        $character->save();

        return response()->json([
            'success' => true,
            'message' => $item['name'] . ' comprado!',
            'newGold' => $character->gold,
            'newStats' => $character->fresh()
        ]);
    }
    
    /**
     * Atualiza o nome do personagem.
     */
    public function update(Request $request, $id)
    {
        $character = Character::findOrFail($id);
        $request->validate([ 'name' => 'required|string|max:50' ]);
        $character->name = $request->name;
        $character->save();
        return response()->json(['success' => true]);
    }

    /**
     * Deleta um personagem.
     */
    public function destroy($id)
    {
        $character = Character::findOrFail($id);
        $character->delete();
        return response()->json(['success' => true]);
    }
}