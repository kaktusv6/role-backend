<?php

namespace App\Modules\Games\Controllers\Api;

use App\Modules\Games\Formatters\DetailGame;
use App\Modules\Games\Formatters\LightGame;
use App\Modules\Games\Repositories\IGameRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GamesController extends Controller
{
    public function getAll(IGameRepository $repository, LightGame $formatter): JsonResponse
    {
        return response()->json(
            $formatter->formatList($repository->getAll())
        );
    }

    public function detailByAlias(Request $request, IGameRepository $repository, DetailGame $formatter): JsonResponse
    {
        $validator = Validator::make([
            'alias' => $request->offsetGet('alias'),
        ], [
            'alias' => 'required|string',
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $game = $repository->getDetailByAlias($request->offsetGet('alias'));
        if ($game === null)
            throw new NotFoundHttpException('Игра не найдена');

        return response()->json(
             $formatter->format($game)
        );
    }
}
