<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockLog;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StockLogController extends Controller
{
    public function index()
    {
        $logs = StockLog::with('product','user')->orderBy('created_at','desc')->get();
        return response()->json($logs);
    }

    public function show($id)
    {
        $log = StockLog::with('product','user')->find($id);
        if(!$log){
            return response()->json(['message'=>'Stock log not found'], 404);
        }
        return response()->json($log);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'=>'required|exists:products,id',
            'change'=>'required|integer',
            'reason'=>'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->quantity += $request->change;
        $product->save();

        $log = StockLog::create([
            'product_id'=>$product->id,
            'user_id'=>Auth::id() ?? 1, // User must exist
            'change'=>$request->change,
            'reason'=>$request->reason,
            'status'=>'active',
            'created_at'=>now()
        ]);

        return response()->json([
            'message'=>'Stock updated successfully',
            'stock_log'=>$log,
            'current_quantity'=>$product->quantity
        ]);
    }

    public function destroy($id)
    {
        $log = StockLog::find($id);
        if(!$log){
            return response()->json(['message'=>'Stock log not found'], 404);
        }
        $log->delete();
        return response()->json(['message'=>'Stock log deleted']);
    }
}