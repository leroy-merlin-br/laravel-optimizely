<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OptimizelyController extends BaseController
{
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function webhook(Request $request): JsonResponse
    {
        $datafileUrl = $request->input('data')['cdn_url'];

        if (!$datafileUrl) {
            return response()->json(['Could not get datafile URL'], 400);
        }

        try {
            $datafileContents = file_get_contents($datafileUrl);
        } catch (Exception $e) {
            return response()->json(['Could not get datafile contents'], 400);
        }

        $filename = $this->config->get('optimizely.path') . '/' . 'optimizely_datafile';
        Storage::disk($this->config->get('optimizely.disk'))->put($filename, $datafileContents);

        return response()->json([], 201);
    }
}
