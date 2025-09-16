<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Record New Payment') }}
            </h2>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Payments
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('payments.store') }}" class="needs-validation" novalidate>
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="installment_plan_id" class="form-label required">Installment Plan</label>
                                <select class="form-select @error('installment_plan_id') is-invalid @enderror" 
                                        id="installment_plan_id" name="installment_plan_id" required>
                                    <option value="">Select Installment Plan</option>
                                    @foreach($installmentPlans as $plan)
                                        <option value="{{ $plan->id }}" 
                                                data-amount="{{ $plan->installment_amount }}"
                                                {{ old('installment_plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->customer->name }} - {{ $plan->product->name }} 
                                            (Plan #{{ $plan->id }} - ${{ number_format($plan->installment_amount, 2) }}/installment)
                                        </option>
                                    @endforeach
                                </select>
                                @error('installment_plan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="amount" class="form-label required">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label required">Payment Method</label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                        <i class="fas fa-money-bill"></i> Cash
                                    </option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                        <i class="fas fa-university"></i> Bank Transfer
                                    </option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>
                                        <i class="fas fa-credit-card"></i> Card
                                    </option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>
                                        <i class="fas fa-money-check"></i> Cheque
                                    </option>
                                    <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>
                                        <i class="fas fa-globe"></i> Online
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
                                       value="{{ old('payment_date', date('Y-m-d')) }}" required>
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
                                       value="{{ old('reference_number') }}" 
                                       placeholder="Transaction ID, Cheque Number, etc.">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="1" 
                                          placeholder="Additional payment information">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Record Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Plan Details Card (shown when plan is selected) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4" id="planDetails" style="display: none;">
                <div class="p-6">
                    <h5 class="mb-3"><i class="fas fa-info-circle me-1"></i> Plan Details</h5>
                    <div id="planInfo"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-fill amount when plan is selected
        document.getElementById('installment_plan_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const amount = selectedOption.getAttribute('data-amount');
            
            if (amount) {
                document.getElementById('amount').value = amount;
                
                // Show plan details
                document.getElementById('planDetails').style.display = 'block';
                document.getElementById('planInfo').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Customer:</strong> ${selectedOption.text.split(' - ')[0]}</p>
                            <p><strong>Product:</strong> ${selectedOption.text.split(' - ')[1].split(' (')[0]}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Installment Amount:</strong> $${parseFloat(amount).toFixed(2)}</p>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('planDetails').style.display = 'none';
            }
        });

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