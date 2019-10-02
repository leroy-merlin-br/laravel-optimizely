<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OptimizelyController extends BaseController
{
    public function webhook(Request $request): JsonResponse
    {
        $datafileUrl = $request->input('data')['cdn_url'];

        if (!$datafileUrl) {
            return response()->json(['Could not get datafile URL'], 400);
        }

        $datafileContents = file_get_contents($datafileUrl);

        if (false === $datafileContents) {
            return response()->json(['Could not get datafile contents'], 400);
        }

        $filename = config('optimizely.path') . '/' . 'optimizely_datafile';

        if (!file_put_contents($filename, $datafileContents)) {
            return response()->json(['Could not save file :('], 500);
        }

        return response()->json([], 201);
    }
}
