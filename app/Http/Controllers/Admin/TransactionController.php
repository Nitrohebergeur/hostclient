<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with(['user', 'invoice', 'gateway'])
            ->when($request->search, fn($q, $s) => $q->where('transaction_id', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->latest()
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'invoice', 'gateway']);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaction supprimée.');
    }
}
