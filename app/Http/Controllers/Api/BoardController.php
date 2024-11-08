<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Board;
use Illuminate\Support\Facades\DB;
use DateTime;

class BoardController extends Controller
{
    private function renderPeriod(Request $request)
    {
        $period = $request->query('period');
        if (!in_array($period, ["day","week","month", null])) {
            return null;
        }
        switch ($period) {
            case "month":
                $periodStart = new DateTime('first day of this month');
                break;
            case "week":
                $periodStart = new DateTime('monday this week');
                break;
            default:
                $periodStart = new DateTime('today');
        }
        return $periodStart->setTime(0, 0, 0);
    }
    /**
     * Show top.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $periodStart = $this->renderPeriod($request);
        if (!$periodStart) {
            return response()->json(['error'=>'Некорректные параметры запроса'], 400);
        }

        $result = Board::select('user_id', DB::raw('SUM(score) as total_score'))
            ->where('created_at', '>=', $periodStart)
            ->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->limit(10)
            ->with('user') // Загружаем связь с пользователями
            ->get();

        // Преобразуем результат в нужный формат
        $topList = [];
        foreach ($result as $key => $board) {
            $topList[$key + 1] = [ // Индексация с 1
                'user_id' => $board->user_id,
                'username' => $board->user->name,
                'score' => (int)$board->total_score,
            ];
        }
        return response()->json([
            'period' => $request->query('period') ?? "day",
            'scores' => $topList,
        ], 200);
    }

    /**
     * Add score in board by user.
     *
     * @return JsonResponse
     */
    public function rank($user_id, Request $request) 
    {
        $periodStart = $this->renderPeriod($request);
        if (!$periodStart) {
            return response()->json(['error'=>'Некорректные параметры запроса'], 400);
        }
        
        $userScore = Board::select(DB::raw('SUM(score) as total_score'))
            ->where('created_at', '>=', $periodStart)
            ->where('user_id', $user_id)
            ->first();
        if (!$userScore) {
            return response()->json(['error'=>'Пользователь не найден'], 404);
        }
        // Получаем пользователей с их суммарными очками
        $rankQuery = Board::select('user_id', DB::raw('SUM(score) as total_score'))
            ->where('created_at', '>=', $periodStart)
            ->groupBy('user_id');

        // Получаем ранг, подсчитывая количество пользователей с более высоким счётом
        $rank = $rankQuery->where('user_id', '<>', $user_id)
            ->havingRaw('SUM(score) > ?', [$userScore->total_score])
            ->count('user_id');

        // Ранг = число пользователей с более высоким счётом + 1
        $rankValue = $rank + 1;
        return response()->json([
            "user_id" => $user_id, 
            "period" => $request->query('period') ?? "day",
            "score" => $userScore->total_score,
            "rank" => $rankValue,
        ], 200);
    }
}
