<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Product Details: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Product Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $product->category }}</td>
                                </tr>
                                <tr>
                                    <th>SKU:</th>
                                    <td>{{ $product->sku }}</td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td>Rs. {{ number_format($product->price) }}</td>
                                </tr>
                                <tr>
                                    <th>Stock:</th>
                                    <td>{{ $product->stock_quantity }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>