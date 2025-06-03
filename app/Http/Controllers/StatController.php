<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stat; 

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request)
    {
        $categories = Stat::query()
        ->select('category')
        ->distinct()
        ->get()
        ->pluck('category');
        return response()->json($categories);
    }

    /**
     * Display a listing of keys for a specific category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $category
     * @return \Illuminate\Http\Response
     */
    public function keysByCategory(Request $request, $category)
    {
        $keys = Stat::query()
        ->select('key')
        ->where('category', $category)
        ->distinct()
        ->get()
        ->pluck('key');
        return response()->json($keys);
    }

    /**
     * Display a listing of stats for a specific player.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid|$username
     * @return \Illuminate\Http\Response
     */
    public function statsByPlayer(Request $request, $uuidOrUsername)
    {
        $player = \App\Models\Player::where('uuid', $uuidOrUsername)
            ->orWhere('username', $uuidOrUsername)
            ->first();

        if (!$player) {
            return response()->json(['error' => 'Player not found'], 404);
        }

        $stats = Stat::where('uuid', $player->uuid)->get();
        return response()->json($stats);
    }
    
}
