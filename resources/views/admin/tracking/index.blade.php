@extends('layouts.admin')

@section('page_title', 'Tracking Logs')

@section('content')
<div class="dashboard-grid">
    <div class="table-card" style="grid-column: span 12;">
        <div class="card-header">
            <h3>User Tracking & Analytics</h3>
        </div>
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>User Identity</th>
                        <th>Event</th>
                        <th>Page URL</th>
                        <th>Interaction Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trackings as $track)
                    <tr>
                        <td style="font-size: 0.85rem; color: var(--text-muted);">
                            {{ $track->created_at->format('M d, Y h:i A') }}<br>
                            <small>{{ $track->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($track->email)
                                <div style="font-weight: 600; color: var(--primary);">{{ $track->email }}</div>
                            @endif
                            @if($track->user_id)
                                <div style="font-size: 0.8rem; color: #10b981;">User ID: {{ $track->user_id }}</div>
                            @endif
                            <div style="font-size: 0.75rem; color: #94a3b8; font-family: monospace;">
                                Session: {{ substr($track->session_id, 0, 8) }}...
                            </div>
                        </td>
                        <td>
                            @if($track->event_type == 'page_view')
                                <span class="badge" style="background: #e0f2fe; color: #0284c7;">Page View</span>
                            @elseif($track->event_type == 'email_submit')
                                <span class="badge" style="background: #fdf4ff; color: #c026d3;">Email Captured</span>
                            @else
                                <span class="badge badge-green">Click</span>
                            @endif
                        </td>
                        <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $track->url }}">
                            <a href="{{ $track->url }}" target="_blank" style="color: var(--primary); text-decoration: none; font-size: 0.85rem;">
                                {{ str_replace(url('/'), '', $track->url) ?: '/' }}
                            </a>
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-main);">
                            @if($track->element_text)
                                <div style="background: #f8fafc; padding: 0.25rem 0.5rem; border-radius: 4px; border: 1px solid var(--border-color); display: inline-block;">
                                    {{ $track->element_text }}
                                </div>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                            <i class="fas fa-satellite-dish" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5;"></i><br>
                            No tracking data collected yet. As users browse your store, their activity will appear here automatically.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem;">
            {{ $trackings->links() }}
        </div>
    </div>
</div>
@endsection
