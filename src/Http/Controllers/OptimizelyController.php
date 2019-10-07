<?php
namespace LeroyMerlin\Optimizely\Http\Controllers;

use LeroyMerlin\Optimizely\Http\Middleware\WebHookToken;
use LeroyMerlin\Optimizely\Http\Requests\WebhookRequest;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class OptimizelyController extends BaseController
{
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->middleware(WebHookToken::class);
        $this->config = $config;
    }

    public function webhook(WebhookRequest $request): JsonResponse
    {
        try {
            $datafileContents = file_get_contents($request->getDatafileUrl());
        } catch (Exception $e) {
            return response()->json(['Could not get datafile contents'], 400);
        }

        $filename = $this->config->get('optimizely.path') . '/' . 'optimizely_datafile';
        Storage::disk($this->config->get('optimizely.disk'))->put($filename, $datafileContents);

        return response()->json([], 201);
    }
}
