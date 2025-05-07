<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Total Expenses for the Current Month -->
                    <div class="flex items-center justify-between mt-4">
                        <div>
                            <h3 class="text-2xl font-semibold">Total Expenses This Month</h3>
                            <p class="text-lg text-gray-600 dark:text-gray-400">All your expenses for the current month.
                            </p>
                        </div>
                        <div class="text-2xl font-bold text-indigo-600">
                            ₹{{ number_format($totalThisMonth, 2) }}
                        </div>
                    </div>

                    <!-- Add Expense Button and Export -->
                    <div class="flex space-x-4 mt-6">
                        <a href="{{ route('expenses.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300">
                            + Add Expense
                        </a>
                        <a href="{{ url('/export-expenses-csv') }}"
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg shadow-lg hover:bg-green-700 transition duration-300">
                            ⬇ Export to CSV
                        </a>
                    </div>

                    <!-- Expenses Table -->
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full table-auto border-collapse">
                            <thead class="bg-gray-200 dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-3 text-left">Title</th>
                                    <th class="px-4 py-3 text-left">Amount</th>
                                    <th class="px-4 py-3 text-left">Category</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-left">Description</th>
                                    <th class="px-4 py-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-600 dark:text-gray-200">
                                @forelse ($expenses as $expense)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-3">{{ $expense->title }}</td>
                                        <td class="px-4 py-3">₹{{ number_format($expense->amount, 2) }}</td>
                                        <td class="px-4 py-3">{{ $expense->category->name }}</td>
                                        <td class="px-4 py-3">{{ $expense->date }}</td>
                                        <td class="px-4 py-3">{{ $expense->description }}</td>
                                        <td class="px-4 py-3 space-x-2">
                                            <a href="{{ route('expenses.edit', $expense) }}"
                                                class="text-yellow-500 hover:text-yellow-600 transition duration-300">Edit</a>
                                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-600 transition duration-300"
                                                    onclick="return confirm('Delete this expense?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No expenses found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>