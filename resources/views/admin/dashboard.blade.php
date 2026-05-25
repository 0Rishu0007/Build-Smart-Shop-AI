<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-black tracking-tight">Admin analytics</h1>
    </x-slot>
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <section class="grid gap-5 md:grid-cols-4">
            @foreach([
                ['Total users', $stats['users']],
                ['Sales', '$'.number_format((float) $stats['sales'], 2)],
                ['Products', $stats['products']],
                ['Behavior events', $stats['events']],
            ] as $stat)
                <div class="surface p-6">
                    <p class="text-sm font-bold text-slate-500">{{ $stat[0] }}</p>
                    <p class="mt-3 text-3xl font-black">{{ $stat[1] }}</p>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="surface p-6">
                <h2 class="text-xl font-black">Most viewed products</h2>
                <div class="mt-5 space-y-4">
                    @foreach($trending as $product)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image() }}" class="h-12 w-12 rounded-xl object-cover" alt="">
                                <div>
                                    <p class="font-bold">{{ $product->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $product->brand->name }}</p>
                                </div>
                            </div>
                            <strong>{{ $product->views_count }} views</strong>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="surface p-6">
                <h2 class="text-xl font-black">Category popularity</h2>
                <div class="mt-5 space-y-4">
                    @foreach($categories as $category)
                        <div>
                            <div class="flex justify-between text-sm font-bold"><span>{{ $category->name }}</span><span>{{ $category->products_count }}</span></div>
                            <div class="mt-2 h-3 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-3 rounded-full bg-indigo-600" style="width: {{ min(100, $category->products_count) }}%"></div></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="surface p-6">
            <h2 class="text-xl font-black">Recent recommendation signals</h2>
            <div class="mt-5 grid gap-3 md:grid-cols-2">
                @foreach($activity as $event)
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm dark:bg-white/5">
                        <strong>{{ ucfirst(str_replace('_', ' ', $event->interaction_type)) }}</strong>
                        <span class="text-slate-500">on {{ $event->product->name ?? 'deleted product' }} · score {{ $event->interaction_score }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
