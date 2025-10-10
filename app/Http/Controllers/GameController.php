<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;

class GameController extends Controller
{
    public function create()
    {
        return view('game.index');
    }

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
            'level' => 1, 'exp' => 0,
        ]);

        return redirect()->route('character.tutorial', $character->id);
    }

    public function tutorial($id)
    {
        $character = Character::findOrFail($id);
        return view('game.tutorial', compact('character'));
    }

    public function allocate($id)
    {
        $character = Character::findOrFail($id);
        return view('game.allocate', compact('character'));
    }

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

        $character->update($request->only([
            'hp','mp','attack','defense','speed','special_attack','special_defense'
        ]));
        
        return redirect()->route('character.play', $character->id);
    }

    public function play($id)
    {
        $character = Character::findOrFail($id);
        return view('game.play', compact('character'));
    }

    // ===== MÉTODO CORRIGIDO =====
    public function play2($id) // Trocado de $characterId para $id
    {
        $character = Character::findOrFail($id);
        // Adicionado 'game.' para consistência
        return view('game.play2', compact('character'));
    }

    // ===== MÉTODO CORRIGIDO E SIMPLIFICADO =====
    public function play3($id)
    {
        $character = Character::findOrFail($id); // Simplificado
        // Adicionado 'game.' para consistência
        return view('game.play3', compact('character'));
    }

    public function attack(Request $request)
    {
        $character = Character::findOrFail($request->id);
        $enemyHp = 50;
        $damage = $character->attack - rand(0,5);
        $enemyHp -= $damage;

        return response()->json([
            'message' => "{$character->name} atacou causando {$damage} de dano!",
            'enemy_hp' => max(0, $enemyHp),
        ]);
    }

    public function update(Request $request, $id)
    {
        $character = Character::findOrFail($id);
        $request->validate([ 'name' => 'required|string|max:50' ]);
        $character->name = $request->name;
        $character->save();

        return response()->json(['success' => true]);
    }

    // DENTRO DE app/Http/Controllers/GameController.php

// ... (depois dos seus outros métodos)

public function saveProgress(Request $request, $id)
{
    $character = Character::findOrFail($id);

    // Atualiza o personagem com os dados recebidos do JavaScript
    $character->update([
        'hp' => $request->input('maxHp'), // Salva o HP máximo
        'mp' => $request->input('maxMp'), // Salva o MP máximo
        'attack' => $request->input('attack'),
        'defense' => $request->input('defense'),
        'special_attack' => $request->input('sp_attack'),
        'special_defense' => $request->input('sp_defense'),
        'speed' => $request->input('speed'),
        'level' => $request->input('level'),
        'exp' => $request->input('xp'),
    ]);

    return response()->json(['success' => true]);
}



    public function destroy($id)
    {
        $character = Character::findOrFail($id);
        $character->delete();

        return response()->json(['success' => true]);
    }
}