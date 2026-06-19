@props(['href', 'active' => false, 'icon'])

<a href="{{ $href }}"
   class="kirri-nav-link kirri-motion active:scale-[0.98] {{ $active ? 'kirri-nav-link-active' : '' }}">
    <span class="shrink-0 kirri-motion {{ $active ? 'text-white' : '' }}" style="{{ !$active ? 'color: var(--kirri-text-muted)' : '' }}">{{ $icon }}</span>
    <span class="truncate">{{ $slot }}</span>
</a>
