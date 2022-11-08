<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Jobs\SyncToDispatcher;
use App\Models\Category;
use Illuminate\Http\Request;

class DispatcherController extends Controller
{
    
    public function categoryProductSyncDispatcher(Request $request)
    {
        if(@$request->order_panel_id){        
        SyncToDispatcher::dispatch($request->all())->onQueue('sync_dispatcher');
        return response()->json([
            'status' => 200,
            'message' => 'Syncing is processing',
            // 'data' => $categories
        ]);
    }else{
        return response()->json([
            'status' => 400,
            'message' => 'order panel id is missing',
            // 'data' => $categories
        ]);
    }
       
    }
}
