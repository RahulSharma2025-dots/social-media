@extends('layouts.main')

@section('title', 'Wallet')

@section('main-content')
<div class="container">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Wallet Balance</h5>
                    <div class="text-center mb-4">
                        <h2 class="mb-0">${{ number_format(auth()->user()->wallet_balance, 2) }}</h2>
                        <small class="text-muted">Available Balance</small>
                    </div>
                    <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#addFundsModal">
                        <i class="fas fa-plus"></i> Add Funds
                    </button>
                    @if(auth()->user()->user_type === 'influencer' && auth()->user()->wallet_balance >= 100)
                    <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        <i class="fas fa-money-bill-wave"></i> Withdraw
                    </button>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Stats</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <small class="text-muted d-block">Total Earned</small>
                            <strong>${{ number_format($totalEarned, 2) }}</strong>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted d-block">Total Spent</small>
                            <strong>${{ number_format($totalSpent, 2) }}</strong>
                        </li>
                        <li class="mb-2">
                            <small class="text-muted d-block">Pending Withdrawals</small>
                            <strong>${{ number_format($pendingWithdrawals, 2) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Transaction History</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary active">All</button>
                            <button type="button" class="btn btn-outline-primary">Deposits</button>
                            <button type="button" class="btn btn-outline-primary">Withdrawals</button>
                            <button type="button" class="btn btn-outline-primary">Purchases</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->type === 'deposit' ? 'success' : 
                                            ($transaction->type === 'withdrawal' ? 'warning' : 
                                            ($transaction->type === 'purchase' ? 'info' : 'primary')) }}">
                                            {{ ucfirst($transaction->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                    <td class="{{ $transaction->type === 'deposit' || $transaction->type === 'earning' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->type === 'deposit' || $transaction->type === 'earning' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : 
                                            ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Funds Modal -->
<div class="modal fade" id="addFundsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Funds</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addFundsForm">
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="amount" min="1" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addFundsButton">Add Funds</button>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Withdraw Funds</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="withdrawForm">
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="amount" min="100" step="0.01" required>
                        </div>
                        <small class="text-muted">Minimum withdrawal amount is $100</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Withdrawal Method</label>
                        <select class="form-select" name="withdrawal_method" required>
                            <option value="bank_account">Bank Account</option>
                            <option value="paypal">PayPal</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="withdrawButton">Withdraw</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle Add Funds
    $('#addFundsButton').click(function() {
        const form = $('#addFundsForm');
        const amount = form.find('[name="amount"]').val();
        const paymentMethod = form.find('[name="payment_method"]').val();

        // Here you would typically integrate with a payment gateway
        // For now, we'll just show a success message
        alert('Payment integration would go here. Amount: $' + amount + ', Method: ' + paymentMethod);
    });

    // Handle Withdraw
    $('#withdrawButton').click(function() {
        const form = $('#withdrawForm');
        const amount = form.find('[name="amount"]').val();
        const withdrawalMethod = form.find('[name="withdrawal_method"]').val();

        if (amount < 100) {
            alert('Minimum withdrawal amount is $100');
            return;
        }

        // Here you would typically process the withdrawal
        // For now, we'll just show a success message
        alert('Withdrawal request submitted. Amount: $' + amount + ', Method: ' + withdrawalMethod);
    });
});
</script>
@endsection 