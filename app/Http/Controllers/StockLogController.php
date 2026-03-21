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
public function list(Request $request)
{
    $search = $request->input('search');
    $perPage = $request->input('per_page', 10);

    $query = StockLog::with('product');

    if ($search) {
        // assume searching by product name
        $query->whereHas('product', function($q) use ($search) {
            $q->where('name', 'like', "%$search%");
        });
    }

    $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

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


    

public function update(Request $request, $id)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'change'     => 'required|integer',
        'reason'     => 'nullable|string',
    ]);
 
    // Find existing log
    $log = StockLog::findOrFail($id);
 
    // Undo old change (ត្រឡប់ quantity ដើម)
    $oldProduct = Product::findOrFail($log->product_id);
    $oldProduct->quantity -= $log->change;
    $oldProduct->save();
 
    // Apply new change
    $newProduct = Product::findOrFail($request->product_id);
    $newProduct->quantity += $request->change;
    $newProduct->save();
 
    // Update stock log (same fields as store)
    $log->update([
        'product_id' => $request->product_id,
        'change'     => $request->change,
        'reason'     => $request->reason,
        'date'       => now(),
    ]);
 
    return response()->json([
        'message'          => 'Stock updated successfully',
        'stock_log'        => $log,
        'current_quantity' => $newProduct->quantity,
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



    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'change'     => 'required|integer',
        'reason'     => 'nullable|string',
    ]);

    // Find product
    $product = Product::findOrFail($request->product_id);

    // Update quantity
    $product->quantity += $request->change;
    $product->save();

    // Create stock log (ONLY fields in your table)
    $log = StockLog::create([
        'product_id' => $product->id,
        'change'     => $request->change,
        'reason'     => $request->reason,
        'date'       => now(), // use your column
    ]);

    return response()->json([
        'message' => 'Stock updated successfully',
        'stock_log' => $log,
        'current_quantity' => $product->quantity
    ]);
}
}