<?php

namespace App\Modules\Characteristics\Controllers\Api;

use App\Modules\Characteristics\Formatters\DetailCharacteristicFormatter;
use App\Modules\Characteristics\Repositories\ICharacteristicRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CharacteristicController extends Controller
{
    public function detailByAlias(
        Request $request,
        ICharacteristicRepository $repository,
        DetailCharacteristicFormatter $formatter,
    ): JsonResponse {
        $validator = Validator::make([
            'alias' => $request->offsetGet('alias'),
        ], [
            'alias' => 'required|string',
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $alias = $request->offsetGet('alias');
        $characteristic = $repository->getDetailByAlias($alias);

        if ($characteristic === null)
            throw new NotFoundHttpException('Характеристика не найдена');

        return response()->json(
            $formatter->format($characteristic)
        );
    }
}
