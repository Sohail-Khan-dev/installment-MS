<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Overdue Payments Report') }}
            </h2>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overdue Summary -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Total Overdue</div>
                            <div class="text-2xl font-bold text-red-600">
                                ${{ number_format($totalOverdue, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Overdue Payments</div>
                            <div class="text-2xl font-bold text-orange-600">
                                {{ $overduePayments->total() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Affected Customers</div>
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ $overduePayments->pluck('customer_id')->unique()->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500">Avg. Days Overdue</div>
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $overduePayments->count() > 0 ? 
                                    round($overduePayments->avg(function($payment) { 
                                        return now()->diffInDays($payment->due_date); 
                                    })) : 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overdue by Days Breakdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Overdue Breakdown by Days</h3>
                    <div class="row">
                        @foreach($overdueByDays as $range => $count)
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="text-sm text-gray-500">{{ $range }}</div>
                                <div class="text-xl font-semibold 
                                    @if(str_contains($range, 'Over 60')) text-red-600
                                    @elseif(str_contains($range, '31-60')) text-orange-600
                                    @elseif(str_contains($range, '8-30')) text-yellow-600
                                    @else text-blue-600
                                    @endif">
                                    {{ $count }} payment{{ $count !== 1 ? 's' : '' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Overdue Payments List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Overdue Payment Details</h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Due Date</th>
                                    <th>Days Overdue</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Amount Due</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overduePayments as $payment)
                                @php
                                    $daysOverdue = now()->diffInDays($payment->due_date);
                                @endphp
                                <tr>
                                    <td>#{{ $payment->id }}</td>
                                    <td>{{ $payment->due_date ? $payment->due_date->format('Y-m-d') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($daysOverdue > 60) bg-danger
                                            @elseif($daysOverdue > 30) bg-warning
                                            @else bg-info
                                            @endif">
                                            {{ $daysOverdue }} days
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $payment->installmentPlan->customer->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Plan #{{ $payment->installment_plan_id }}
                                        </small>
                                    </td>
                                    <td>{{ $payment->installmentPlan->product->name ?? 'N/A' }}</td>
                                    <td class="font-semibold text-danger">
                                        ${{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td>
                                        @if($payment->installmentPlan && $payment->installmentPlan->customer)
                                            <small>
                                                📱 {{ $payment->installmentPlan->customer->phone }}<br>
                                                ✉️ {{ $payment->installmentPlan->customer->email }}
                                            </small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('payments.show', $payment) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form method="POST" action="{{ route('payments.mark-paid', $payment) }}" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        title="Mark as Paid"
                                                        onclick="return confirm('Mark this payment as paid?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Great! No overdue payments at this time.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $overduePayments->links() }}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6">
                    <h4 class="mb-3">Quick Actions</h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Print Report
                        </button>
                        <a href="mailto:?subject=Overdue Payments Report&body=Please review the overdue payments report." 
                           class="btn btn-info">
                            <i class="fas fa-envelope me-1"></i> Email Report
                        </a>
                        <button class="btn btn-success" onclick="exportToCSV()">
                            <i class="fas fa-file-excel me-1"></i> Export to Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function exportToCSV() {
            alert('Export functionality would be implemented here to download overdue payments as CSV.');
        }
    </script>
    @endpush
</x-app-layout>