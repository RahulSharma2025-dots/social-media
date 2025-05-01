<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $totalEarned = WalletTransaction::where('user_id', $user->id)
            ->whereIn('type', ['earning'])
            ->sum('amount');

        $totalSpent = WalletTransaction::where('user_id', $user->id)
            ->whereIn('type', ['purchase'])
            ->sum('amount');

        $pendingWithdrawals = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'withdrawal')
            ->where('status', 'pending')
            ->sum('amount');

        return view('wallet', compact('transactions', 'totalEarned', 'totalSpent', 'pendingWithdrawals'));
    }

    public function deposit(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer'
        ]);

        // Here you would typically integrate with a payment gateway
        // For now, we'll just create a transaction record
        $transaction = WalletTransaction::create([
            'user_id' => auth()->id(),
            'type' => 'deposit',
            'amount' => $validated['amount'],
            'status' => 'completed',
            'description' => 'Deposit via ' . $validated['payment_method'],
            'transaction_id' => uniqid('DEP_')
        ]);

        // Update user's wallet balance
        $user = auth()->user();
        $user->wallet_balance += $validated['amount'];
        $user->save();

        return redirect()->route('wallet')->with('success', 'Funds added successfully');
    }

    public function withdraw(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'withdrawal_method' => 'required|in:bank_account,paypal'
        ]);

        $user = auth()->user();

        if ($user->wallet_balance < $validated['amount']) {
            return back()->with('error', 'Insufficient balance');
        }

        // Create withdrawal transaction
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $validated['amount'],
            'status' => 'pending',
            'description' => 'Withdrawal via ' . $validated['withdrawal_method'],
            'transaction_id' => uniqid('WD_')
        ]);

        // Update user's wallet balance
        $user->wallet_balance -= $validated['amount'];
        $user->save();

        return redirect()->route('wallet')->with('success', 'Withdrawal request submitted successfully');
    }
} 