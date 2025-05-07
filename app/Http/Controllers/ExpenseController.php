<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use App\Exports\ExpensesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        // Fetch user expenses, with category relationship
        $query = Expense::with('category')
            ->where('user_id', auth()->id());

        // Filtering by category if provided
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtering by date range if provided
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('date', [$request->from, $request->to]);
        }

        $expenses = $query->latest()->paginate(10);

        // Get the total amount for the current month
        $totalThisMonth = Expense::where('user_id', auth()->id())
            ->whereMonth('date', now()->month)
            ->sum('amount');

        $categories = Category::where(function ($q) {
            $q->whereNull('user_id')
                ->orWhere('user_id', auth()->id());
        })->get();

        return view('dashboard', compact('expenses', 'totalThisMonth', 'categories'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('expenses.create', compact('categories'));
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

        Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'date' => $request->date,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        // Redirect back to the expenses page
        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');

    }

    public function show(Expense $expense)
    {
        //
    }


    public function edit(Expense $expense)
    {
        // Fetch categories based on the logged-in user
        $categories = Category::where('user_id', auth()->id())->get();

        // Check if the user has no categories
        if ($categories->isEmpty()) {
            // If no categories, create default ones
            $this->createDefaultCategories(auth()->user());
            // Fetch categories again
            $categories = Category::where('user_id', auth()->id())->get();
        }
        // Ensure date is correctly parsed
        $expense->date = Carbon::parse($expense->date);

        // Return the 'edit' view and pass both 'expense' and 'categories' variables
        return view('expenses.edit', compact('expense', 'categories'));
    }
    // Function to create default categories for the user
    protected function createDefaultCategories(User $user)
    {
        $defaultCategories = ['Food', 'Transport', 'Entertainment', 'Shopping', 'Bills', 'Health'];

        foreach ($defaultCategories as $category) {
            Category::create([
                'name' => $category,
                'user_id' => $user->id
            ]);
        }
    }


    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Update the expense
        $expense->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');

    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');

    }

    public function exportCsv()
    {
        return Excel::download(new ExpensesExport, 'expenses.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
