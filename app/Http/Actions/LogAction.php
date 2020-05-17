<?php

namespace App\Http\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class LogAction extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            Log::driver('boa')->info('log:' . (!empty($request->info) ? $request->info : 'test'));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return response()->json([
            'success' => true,
            'message' => 'logged',
        ]);
    }
}
