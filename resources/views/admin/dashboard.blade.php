@extends('layouts.admin')

@section('title', 'Admin Dashboard - ModestMirror')
@section('page_title', 'Administrative Overview')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat 1: Revenue -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-monospace text-uppercase text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Revenue</h6>
                    <h3 class="brand-font mb-0 text-success">PKR {{ number_format($stats['revenue']) }}</h3>
                </div>
                <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-coins fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Orders -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-monospace text-uppercase text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Orders</h6>
                    <h3 class="brand-font mb-0 text-dark">{{ $stats['orders'] }}</h3>
                </div>
                <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-receipt fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Products -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-monospace text-uppercase text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Catalog Items</h6>
                    <h3 class="brand-font mb-0 text-dark">{{ $stats['products'] }}</h3>
                </div>
                <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-shirt fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 4: Registered Users -->
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="font-monospace text-uppercase text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Users</h6>
                    <h3 class="brand-font mb-0 text-dark">{{ $stats['users'] }}</h3>
                </div>
                <div class="rounded-3 p-3 bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-users fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card border-0 shadow-sm rounded-4 bg-white">
    <div class="card-header bg-white py-3 border-0">
        <h4 class="brand-font mb-0"><i class="fa-solid fa-clock-rotate-left text-gold me-2"></i>Recent Sales Orders</h4>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light font-monospace text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                <tr>
                    <th class="p-3 border-0">Order Number</th>
                    <th class="p-3 border-0">Customer Name</th>
                    <th class="p-3 border-0">Date</th>
                    <th class="p-3 border-0">Total Amount</th>
                    <th class="p-3 border-0 text-center">Status</th>
                    <th class="p-3 border-0"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td class="p-3 border-bottom-0"><strong>#{{ $order->order_number }}</strong></td>
                        <td class="p-3 border-bottom-0">{{ $order->user->name }}</td>
                        <td class="p-3 border-bottom-0 text-muted">{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td class="p-3 border-bottom-0 fw-semibold">PKR {{ number_format($order->total_amount) }}</td>
                        <td class="p-3 border-bottom-0 text-center">
                            @if($order->status === 'completed')
                                <span class="badge bg-success text-uppercase px-3 py-2">Completed</span>
                            @elseif($order->status === 'shipped')
                                <span class="badge bg-primary text-uppercase px-3 py-2">Shipped</span>
                            @elseif($order->status === 'processing')
                                <span class="badge bg-warning text-dark text-uppercase px-3 py-2">Processing</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge bg-danger text-uppercase px-3 py-2">Cancelled</span>
                            @else
                                <span class="badge bg-secondary text-uppercase px-3 py-2">Pending</span>
                            @endif
                        </td>
                        <td class="p-3 border-bottom-0 text-end">
                            <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-luxury-outline py-1 px-3">Manage</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No orders found in the database.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
