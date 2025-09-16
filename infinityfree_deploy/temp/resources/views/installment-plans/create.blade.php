<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Installment Plan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('installment-plans.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">Customer</label>
                                    <select class="form-control @error('customer_id') is-invalid @enderror" 
                                            id="customer_id" name="customer_id" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->full_name }} - {{ $customer->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select class="form-control @error('product_id') is-invalid @enderror" 
                                            id="product_id" name="product_id" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" 
                                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} - Rs. {{ number_format($product->price) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="down_payment" class="form-label">Down Payment</label>
                                    <input type="number" step="0.01" class="form-control @error('down_payment') is-invalid @enderror" 
                                           id="down_payment" name="down_payment" value="{{ old('down_payment', 0) }}" required>
                                    @error('down_payment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="number_of_installments" class="form-label">Number of Installments</label>
                                    <input type="number" class="form-control @error('number_of_installments') is-invalid @enderror" 
                                           id="number_of_installments" name="number_of_installments" value="{{ old('number_of_installments', 12) }}" required>
                                    @error('number_of_installments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="interest_rate" class="form-label">Interest Rate (%)</label>
                                    <input type="number" step="0.01" class="form-control @error('interest_rate') is-invalid @enderror" 
                                           id="interest_rate" name="interest_rate" value="{{ old('interest_rate', 0) }}">
                                    @error('interest_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_frequency" class="form-label">Payment Frequency</label>
                                    <select class="form-control @error('payment_frequency') is-invalid @enderror" 
                                            id="payment_frequency" name="payment_frequency" required>
                                        <option value="monthly" {{ old('payment_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="weekly" {{ old('payment_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="bi-weekly" {{ old('payment_frequency') == 'bi-weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                        <option value="quarterly" {{ old('payment_frequency') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    </select>
                                    @error('payment_frequency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('installment-plans.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Plan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
