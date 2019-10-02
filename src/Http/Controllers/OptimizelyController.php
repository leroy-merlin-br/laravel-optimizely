<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OptimizelyController extends BaseController
{
    public function webhook(Request $request)
    {
        $datafileUrl = $request->input('data')['cdn_url'];

        if (!$datafileUrl) {
            response()->json(['Could not get datafile URL'], 401);
        }

        $datafileContents = file_get_contents($datafileUrl);
        $filename = config('optimizely.path') . '/' . 'optimizely_datafile';

        if (!file_put_contents($filename, $datafileContents)) {
            response()->json(['Could not save file :('], 500);
        }
        
        response()->json([], 201);
    }
}
