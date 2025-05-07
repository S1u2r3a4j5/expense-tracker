<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;


class ExpenseApiController extends Controller
{

    public function index(Request $request)
    {
        $query = Expense::with('category')
            ->where('user_id', auth()->id());

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('date', [$request->from, $request->to]);
        }

        $expenses = $query->latest()->paginate(10);

        return response()->json([
            'data' => $expenses,
            'total_this_month' => Expense::where('user_id', auth()->id())
                ->whereMonth('date', now()->month)
                ->sum('amount'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expense = Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'date' => $request->date,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return response()->json($expense, 201); // Successfully created
    }

    public function show($id)
    {
        $expense = Expense::findOrFail($id);
        return response()->json($expense);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expense = Expense::findOrFail($id);

        $expense->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return response()->json($expense);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(null, 204); // Successfully deleted
    }
}
