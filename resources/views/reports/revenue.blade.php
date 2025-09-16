<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Revenue Report') }}
            </h2>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Date Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <form method="GET" action="{{ route('reports.revenue') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ request('start_date', $startDate) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ request('end_date', $endDate) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('reports.revenue') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Revenue Summary -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Total Revenue</div>
                            <div class="text-2xl font-bold text-green-600">
                                ${{ number_format($totalRevenue, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Total Payments</div>
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $payments->total() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Average Payment</div>
                            <div class="text-2xl font-bold text-purple-600">
                                ${{ $payments->count() > 0 ? number_format($totalRevenue / $payments->total(), 2) : 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Breakdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Payment Methods Breakdown</h3>
                    <div class="row">
                        @foreach($paymentMethods as $method)
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}</div>
                                        <div class="font-semibold">${{ number_format($method->total, 2) }}</div>
                                    </div>
                                    <div class="text-2xl text-gray-400">
                                        @switch($method->payment_method)
                                            @case('cash')
                                                <i class="fas fa-money-bill"></i>
                                                @break
                                            @case('bank_transfer')
                                                <i class="fas fa-university"></i>
                                                @break
                                            @case('card')
                                                <i class="fas fa-credit-card"></i>
                                                @break
                                            @case('cheque')
                                                <i class="fas fa-money-check"></i>
                                                @break
                                            @case('online')
                                                <i class="fas fa-globe"></i>
                                                @break
                                            @default
                                                <i class="fas fa-dollar-sign"></i>
                                        @endswitch
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $method->count }} transactions</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Payments List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Payment Transactions</h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>#{{ $payment->id }}</td>
                                    <td>{{ $payment->payment_date ? $payment->payment_date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>{{ $payment->installmentPlan->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->installmentPlan->product->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        </span>
                                    </td>
                                    <td class="font-semibold">${{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->reference_number ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500">
                                        No payments found for the selected period.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $payments->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>