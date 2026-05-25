@props(['rating' => 0, 'count' => null])

<div class="flex items-center gap-1 text-sm">
    @for($i = 1; $i <= 5; $i++)
        <span class="{{ $i <= round($rating) ? 'text-amber-400' : 'text-slate-300 dark:text-slate-700' }}">★</span>
    @endfor
    <span class="ml-1 text-xs font-medium text-slate-500">{{ number_format((float) $rating, 1) }}@if(! is_null($count)) ({{ $count }}) @endif</span>
</div>
