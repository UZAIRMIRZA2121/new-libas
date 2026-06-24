@extends('layouts.admin')

@section('page_title', 'Manage Customers')

@section('content')
<div class="dashboard-grid">
    <div class="table-card" style="grid-column: span 12;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Customer List</h3>
            <form action="{{ route('admin.customers.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" placeholder="Search customers..." value="{{ request('search') }}" style="padding: 0.4rem; border: 1px solid var(--border-color); border-radius: 4px;">
                <button type="submit" class="btn" style="background: var(--primary); color: white; padding: 0.4rem 1rem; border: none; border-radius: 4px; cursor: pointer;">Search</button>
            </form>
        </div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td><strong>#{{ $customer->id }}</strong></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @if($customer->avatar)
                                    <img src="{{ asset('storage/' . $customer->avatar) }}" alt="Avatar" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: var(--bg-light); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.8rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div style="font-weight: 500;">{{ $customer->name }}</div>
                            </div>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone ?? 'N/A' }}</td>
                        <td>
                            @if($customer->address || $customer->city)
                                <div style="font-size: 0.85rem;">
                                    {{ $customer->address }} 
                                    @if($customer->apartment) ({{ $customer->apartment }}) @endif
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    {{ $customer->city }} {{ $customer->postal_code }}
                                </div>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.85rem;">N/A</span>
                            @endif
                        </td>
                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.customers.orders', $customer) }}" class="btn" style="background: var(--primary); color: white; padding: 0.35rem 0.75rem; text-decoration: none; border-radius: 4px; font-size: 0.8rem;"><i class="fas fa-shopping-bag"></i> Orders</a>
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn" style="background: #eab308; color: white; padding: 0.35rem 0.75rem; text-decoration: none; border-radius: 4px; font-size: 0.8rem;"><i class="fas fa-edit"></i> Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem;">
            {{ $customers->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
