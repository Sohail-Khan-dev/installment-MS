<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Payment #') }}{{ $payment->id }}
            </h2>
            <a href="{{ route('payments.show', $payment) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Payment
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('payments.update', $payment) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Read-only Plan Information -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Installment Plan:</strong> 
                                    {{ $payment->installmentPlan->customer->name }} - 
                                    {{ $payment->installmentPlan->product->name }} 
                                    (Plan #{{ $payment->installmentPlan->id }})
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label required">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" 
                                           value="{{ old('amount', $payment->amount) }}" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label required">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="pending" {{ old('status', $payment->status) == 'pending' ? 'selected' : '' }}>
                                        <i class="fas fa-clock"></i> Pending
                                    </option>
                                    <option value="paid" {{ old('status', $payment->status) == 'paid' ? 'selected' : '' }}>
                                        <i class="fas fa-check-circle"></i> Paid
                                    </option>
                                    <option value="late" {{ old('status', $payment->status) == 'late' ? 'selected' : '' }}>
                                        <i class="fas fa-exclamation-triangle"></i> Late
                                    </option>
                                    <option value="failed" {{ old('status', $payment->status) == 'failed' ? 'selected' : '' }}>
                                        <i class="fas fa-times-circle"></i> Failed
                                    </option>
                                    <option value="refunded" {{ old('status', $payment->status) == 'refunded' ? 'selected' : '' }}>
                                        <i class="fas fa-undo"></i> Refunded
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label required">Payment Method</label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>
                                        Cash
                                    </option>
                                    <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>
                                        Bank Transfer
                                    </option>
                                    <option value="card" {{ old('payment_method', $payment->payment_method) == 'card' ? 'selected' : '' }}>
                                        Card
                                    </option>
                                    <option value="cheque" {{ old('payment_method', $payment->payment_method) == 'cheque' ? 'selected' : '' }}>
                                        Cheque
                                    </option>
                                    <option value="online" {{ old('payment_method', $payment->payment_method) == 'online' ? 'selected' : '' }}>
                                        Online
                                    </option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="payment_date" class="form-label required">Payment Date</label>
                                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                       id="payment_date" name="payment_date" 
                                       value="{{ old('payment_date', $payment->payment_date ? $payment->payment_date->format('Y-m-d') : '') }}" required>
                                @error('payment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                       id="reference_number" name="reference_number" 
                                       value="{{ old('reference_number', $payment->reference_number) }}" 
                                       placeholder="Transaction ID, Cheque Number, etc.">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="1" 
                                          placeholder="Additional payment information">{{ old('notes', $payment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('payments.show', $payment) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Payment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Bootstrap form validation
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    @endpush

    <style>
        .required:after {
            content: " *";
            color: red;
        }
    </style>
</x-app-layout>