<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Installment Plans') }}
            </h2>
            <a href="{{ route('installment-plans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Plan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Plan #</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Total Amount</th>
                                <th>Installments</th>
                                <th>Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plans as $plan)
                                <tr>
                                    <td>{{ $plan->plan_number }}</td>
                                    <td>{{ $plan->customer->full_name }}</td>
                                    <td>{{ $plan->product->name }}</td>
                                    <td>Rs. {{ number_format($plan->total_amount) }}</td>
                                    <td>{{ $plan->payments_made }}/{{ $plan->number_of_installments }}</td>
                                    <td>Rs. {{ number_format($plan->balance) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $plan->status == 'active' ? 'success' : ($plan->status == 'completed' ? 'info' : 'danger') }}">
                                            {{ ucfirst($plan->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('installment-plans.show', $plan) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $plans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
