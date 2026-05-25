<form action="{{ route('products.index') }}" x-data="{ q: '{{ request('q') }}', suggestions: [], async search(){ if(this.q.length < 2){ this.suggestions=[]; return;} const r = await fetch('{{ route('search.suggestions') }}?q=' + encodeURIComponent(this.q)); this.suggestions = await r.json(); } }" class="relative w-full">
    <input name="q" x-model="q" @input.debounce.250ms="search" autocomplete="off" placeholder="Search for sneakers, headphones, skincare..." class="w-full rounded-full border-0 bg-white px-5 py-4 pr-32 text-sm shadow-xl shadow-slate-900/10 ring-1 ring-slate-200 transition focus:ring-2 focus:ring-indigo-500 dark:bg-slate-900 dark:ring-white/10">
    <button class="absolute right-2 top-2 rounded-full bg-slate-950 px-5 py-2 text-sm font-bold text-white dark:bg-white dark:text-slate-950">Search</button>
    <div x-show="suggestions.length" x-cloak class="absolute z-40 mt-2 w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-white/10 dark:bg-slate-900">
        <template x-for="item in suggestions" :key="item.url">
            <a :href="item.url" class="flex items-center justify-between px-5 py-3 text-sm hover:bg-slate-50 dark:hover:bg-white/5">
                <span x-text="item.name"></span>
                <strong x-text="item.price"></strong>
            </a>
        </template>
    </div>
</form>
