@props(['title', 'description', 'badgeText', 'badgeColor'])

<div class="alert-item">
    <div class="alert-info">
        <h4>{{ $title }}</h4>
        <p>{{ $description }}</p>
    </div>
    <x-admin.badge :colorClass="$badgeColor">{{ $badgeText }}</x-admin.badge>
</div>
