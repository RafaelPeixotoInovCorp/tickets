@props(['status'])

<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border"
      style="background-color: {{ $status->color }}12; color: {{ $status->color }}; border-color: {{ $status->color }}30">
    {{ $status->name }}
</span>
