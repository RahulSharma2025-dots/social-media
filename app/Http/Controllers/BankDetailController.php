<?php

namespace App\Http\Controllers;

use App\Models\BankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('bank.details');
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:11',
            'branch_name' => 'nullable|string|max:255',
        ]);

        $bankDetail = new BankDetail([
            'user_id' => Auth::id(),
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'branch_name' => $request->branch_name,
        ]);

        $bankDetail->save();

        return redirect()->route('home')->with('success', 'Bank details added successfully.');
    }
}
