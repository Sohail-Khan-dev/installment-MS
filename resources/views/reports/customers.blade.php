<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customer Analytics Report') }}
            </h2>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Customer Growth Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">New Customer Trend (Last 6 Months)</h3>
                    <canvas id="customerTrendChart" height="80"></canvas>
                </div>
            </div>

            <!-- Top Customers -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">
                        <i class="fas fa-trophy text-yellow-500 me-2"></i>
                        Top 10 Customers by Purchase Value
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Total Plans</th>
                                    <th>Total Purchases</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers as $index => $customer)
                                <tr>
                                    <td>
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark">🥇 1st</span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary">🥈 2nd</span>
                                        @elseif($index == 2)
                                            <span class="badge bg-danger">🥉 3rd</span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $index + 1 }}th</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $customer->name }}</strong>
                                        <br>
                                        <small class="text-muted">ID: #{{ $customer->id }}</small>
                                    </td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $customer->total_plans ?? 0 }}</span>
                                    </td>
                                    <td class="font-semibold text-success">
                                        ${{ number_format($customer->total_purchases ?? 0, 2) }}
                                    </td>
                                    <td>
                                        @if($customer->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($customer->status == 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @else
                                            <span class="badge bg-danger">Blocked</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-500">
                                        No customer data available.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Payment Behavior -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">
                        <i class="fas fa-chart-pie text-blue-500 me-2"></i>
                        Customer Payment Behavior Analysis
                    </h3>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>On-Time Payments</th>
                                    <th>Late Payments</th>
                                    <th>Payment Reliability</th>
                                    <th>Avg. Delay (Days)</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentBehavior as $customer)
                                @php
                                    $totalPayments = ($customer->on_time_payments ?? 0) + ($customer->late_payments ?? 0);
                                    $reliabilityScore = $totalPayments > 0 ? 
                                        (($customer->on_time_payments ?? 0) / $totalPayments) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $customer->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $customer->on_time_payments ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $customer->late_payments ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar 
                                                @if($reliabilityScore >= 90) bg-success
                                                @elseif($reliabilityScore >= 70) bg-info
                                                @elseif($reliabilityScore >= 50) bg-warning
                                                @else bg-danger
                                                @endif" 
                                                role="progressbar" 
                                                style="width: {{ $reliabilityScore }}%">
                                                {{ number_format($reliabilityScore, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($customer->avg_payment_delay !== null)
                                            @if($customer->avg_payment_delay < 0)
                                                <span class="text-success">
                                                    {{ abs(round($customer->avg_payment_delay)) }} days early
                                                </span>
                                            @elseif($customer->avg_payment_delay > 0)
                                                <span class="text-danger">
                                                    {{ round($customer->avg_payment_delay) }} days late
                                                </span>
                                            @else
                                                <span class="text-muted">On time</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reliabilityScore >= 90)
                                            <span class="text-success">⭐⭐⭐⭐⭐ Excellent</span>
                                        @elseif($reliabilityScore >= 70)
                                            <span class="text-info">⭐⭐⭐⭐ Good</span>
                                        @elseif($reliabilityScore >= 50)
                                            <span class="text-warning">⭐⭐⭐ Average</span>
                                        @elseif($reliabilityScore >= 30)
                                            <span class="text-orange">⭐⭐ Poor</span>
                                        @else
                                            <span class="text-danger">⭐ Very Poor</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">
                                        No payment behavior data available.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="mb-3">Customer Statistics</h4>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-users text-blue-500 me-2"></i>
                                    Total Customers: <strong>{{ $topCustomers->count() }}</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-dollar-sign text-green-500 me-2"></i>
                                    Average Purchase: <strong>${{ number_format($topCustomers->avg('total_purchases') ?? 0, 2) }}</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-file-contract text-purple-500 me-2"></i>
                                    Average Plans per Customer: <strong>{{ number_format($topCustomers->avg('total_plans') ?? 0, 1) }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="mb-3">Quick Actions</h4>
                            <div class="d-grid gap-2">
                                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-1"></i> Add New Customer
                                </a>
                                <button class="btn btn-info" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i> Print Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Customer Trend Chart
        const ctx = document.getElementById('customerTrendChart').getContext('2d');
        const customerTrendChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($newCustomersTrend, 'month')) !!},
                datasets: [{
                    label: 'New Customers',
                    data: {!! json_encode(array_column($newCustomersTrend, 'count')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>