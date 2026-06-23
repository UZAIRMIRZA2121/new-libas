@extends('layouts.admin')

@section('page_title', 'Coupons Management')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main);">Coupons</h2>
        <p style="color: var(--text-muted); font-size: 0.875rem;">Manage discount coupons and view subscribers</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" style="background: var(--primary); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);">
        <i class="fas fa-plus"></i> Add New Coupon
    </a>
</div>

@if(session('success'))
    <div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px; color: #065f46;">
        {{ session('success') }}
    </div>
@endif

<div class="table-card">
    <div class="card-header">
        <h3>All Coupons</h3>
    </div>
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td>#{{ $coupon->id }}</td>
                        <td>
                            @if($coupon->email)
                                <strong>{{ $coupon->email }}</strong>
                            @else
                                <span style="color: var(--text-muted); font-style: italic;">Everyone (General)</span>
                            @endif
                        </td>
                        <td><span style="background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 4px; font-family: monospace; font-weight: bold;">{{ $coupon->code }}</span></td>
                        <td>{{ $coupon->discount_percentage }}%</td>
                        <td>
                            @if($coupon->is_used)
                                <span class="badge badge-yellow">Used</span>
                            @else
                                <span class="badge badge-green">Available</span>
                            @endif
                        </td>
                        <td style="color: var(--text-muted); font-size: 0.8rem;">{{ $coupon->created_at->format('M d, Y h:i A') }}</td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" style="color: #3b82f6; background: #eff6ff; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; text-decoration: none;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: #ef4444; background: #fef2f2; border: none; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; cursor: pointer;" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <div style="font-size: 2rem; margin-bottom: 1rem; color: #cbd5e1;"><i class="fas fa-ticket-alt"></i></div>
                            <p>No coupons found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
