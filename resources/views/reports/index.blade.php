<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports & Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Revenue Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="fas fa-dollar-sign text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Revenue</div>
                                <div class="text-lg font-semibold text-gray-900">${{ number_format($totalRevenue, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="fas fa-calendar-alt text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Monthly Revenue</div>
                                <div class="text-lg font-semibold text-gray-900">${{ number_format($monthlyRevenue, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Overdue Payments</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $overduePayments }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Active Customers</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $activeCustomers }}/{{ $totalCustomers }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Reports</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('reports.revenue') }}" class="bg-blue-50 border border-blue-200 rounded-lg p-4 hover:bg-blue-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-chart-line text-blue-600 text-2xl mr-3"></i>
                                <div>
                                    <div class="font-semibold text-blue-900">Revenue Report</div>
                                    <div class="text-sm text-blue-600">View detailed revenue analytics</div>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('reports.overdue') }}" class="bg-red-50 border border-red-200 rounded-lg p-4 hover:bg-red-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-red-600 text-2xl mr-3"></i>
                                <div>
                                    <div class="font-semibold text-red-900">Overdue Report</div>
                                    <div class="text-sm text-red-600">Track overdue payments</div>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('reports.customers') }}" class="bg-green-50 border border-green-200 rounded-lg p-4 hover:bg-green-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-user-chart text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <div class="font-semibold text-green-900">Customer Analytics</div>
                                    <div class="text-sm text-green-600">Customer behavior insights</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Monthly Trend Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Revenue Trend (Last 6 Months)</h3>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Top Products</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plans Created</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($topProducts as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->category }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($product->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $product->plans_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->quantity > 10 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $product->quantity }} units
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyTrend, 'month')) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode(array_column($monthlyTrend, 'revenue')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    tension: 0.4
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
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>