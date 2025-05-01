@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Add Bank Details</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('bank.details.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="account_holder_name" class="col-md-4 col-form-label text-md-end">Account Holder Name</label>
                            <div class="col-md-6">
                                <input id="account_holder_name" type="text" class="form-control @error('account_holder_name') is-invalid @enderror" 
                                    name="account_holder_name" value="{{ old('account_holder_name') }}" required>
                                @error('account_holder_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="account_number" class="col-md-4 col-form-label text-md-end">Account Number</label>
                            <div class="col-md-6">
                                <input id="account_number" type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                    name="account_number" value="{{ old('account_number') }}" required>
                                @error('account_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="bank_name" class="col-md-4 col-form-label text-md-end">Bank Name</label>
                            <div class="col-md-6">
                                <input id="bank_name" type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                    name="bank_name" value="{{ old('bank_name') }}" required>
                                @error('bank_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="ifsc_code" class="col-md-4 col-form-label text-md-end">IFSC Code</label>
                            <div class="col-md-6">
                                <input id="ifsc_code" type="text" class="form-control @error('ifsc_code') is-invalid @enderror" 
                                    name="ifsc_code" value="{{ old('ifsc_code') }}" required>
                                @error('ifsc_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="branch_name" class="col-md-4 col-form-label text-md-end">Branch Name</label>
                            <div class="col-md-6">
                                <input id="branch_name" type="text" class="form-control @error('branch_name') is-invalid @enderror" 
                                    name="branch_name" value="{{ old('branch_name') }}">
                                @error('branch_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save Bank Details
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 