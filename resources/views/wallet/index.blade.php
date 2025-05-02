@extends('layouts.main')

@section('title', 'My Wallet')

@section('main-content')
<div class="container py-4" style="max-width: 700px;">
    <!-- Wallet Balance -->
    <div class="card mb-4 shadow-sm" style="border-radius: 18px;">
        <div class="card-body d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; font-size: 2rem;">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <div class="text-muted small">Wallet Balance</div>
                <div class="fw-bold" style="font-size: 2rem;">$1,250.00</div>
            </div>
            <div class="ms-auto">
                <a href="#" class="btn btn-outline-primary me-2">Add Funds</a>
                <a href="#" class="btn btn-outline-secondary">Withdraw</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-plus-circle fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">Add Funds</h6>
                    <div class="text-muted small">Top up your wallet</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-arrow-circle-up fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">Withdraw</h6>
                    <div class="text-muted small">Send money to bank</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 text-center shadow-sm" style="border-radius: 14px;">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="fas fa-exchange-alt fa-2x mb-2 text-primary"></i>
                    <h6 class="fw-bold mb-1">Transfer</h6>
                    <div class="text-muted small">Send to another user</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card shadow-sm" style="border-radius: 14px;">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Recent Transactions</h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center">
                    <span class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="fas fa-arrow-down"></i>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-bold">Funds Added</div>
                        <div class="text-muted small">May 20, 2024</div>
                    </div>
                    <div class="fw-bold text-success">+ $500.00</div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <span class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="fas fa-arrow-up"></i>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-bold">Withdrawal</div>
                        <div class="text-muted small">May 18, 2024</div>
                    </div>
                    <div class="fw-bold text-danger">- $200.00</div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="fas fa-exchange-alt"></i>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-bold">Transfer to Mike</div>
                        <div class="text-muted small">May 15, 2024</div>
                    </div>
                    <div class="fw-bold text-primary">- $50.00</div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <span class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                        <i class="fas fa-arrow-down"></i>
                    </span>
                    <div class="flex-grow-1">
                        <div class="fw-bold">Funds Added</div>
                        <div class="text-muted small">May 10, 2024</div>
                    </div>
                    <div class="fw-bold text-success">+ $300.00</div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection 