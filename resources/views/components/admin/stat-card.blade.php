@props(['title', 'value', 'icon', 'colorClass'])

<div class="stat-card" style="grid-column: span 2;">
    <div class="stat-icon {{ $colorClass }}"><i class="{{ $icon }}"></i></div>
    <div class="stat-info">
        <h4>{{ $value }}</h4>
        <p>{{ $title }}</p>
    </div>
</div>
