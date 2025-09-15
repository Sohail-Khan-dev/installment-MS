<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-3">
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">All</a>
                        <a href="{{ route('payments.index', ['status' => 'pending']) }}" class="btn btn-outline-warning">Pending</a>
                        <a href="{{ route('payments.index', ['status' => 'paid']) }}" class="btn btn-outline-success">Paid</a>
                        <a href="{{ route('payments.index', ['overdue' => '1']) }}" class="btn btn-outline-danger">Overdue</a>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Payment #</th>
                                <th>Customer</th>
                                <th>Plan #</th>
                                <th>Amount</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>{{ $payment->customer->full_name }}</td>
                                    <td>{{ $payment->installmentPlan->plan_number }}</td>
                                    <td>Rs. {{ number_format($payment->amount) }}</td>
                                    <td>{{ $payment->due_date->format('Y-m-d') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($payment->status == 'pending')
                                            <form action="{{ route('payments.mark-paid', $payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Mark Paid</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
