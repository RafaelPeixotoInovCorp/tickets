@props(['label', 'value', 'hint' => null])

<div class="kirri-panel p-5">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-kirri-500">{{ $label }}</p>
            @if($hint)
                <p class="text-xs text-kirri-400 mt-0.5">{{ $hint }}</p>
            @endif
        </div>
        @if(isset($icon))
            <div class="w-10 h-10 rounded-xl bg-kirri-primary/10 flex items-center justify-center text-kirri-primary">
                {{ $icon }}
            </div>
        @endif
    </div>
    <p class="text-3xl font-semibold text-kirri-900 mt-3 tracking-tight">{{ $value }}</p>
</div>
