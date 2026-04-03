<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        <flux:heading size="xl">Dashboard</flux:heading>

        <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($totales as $tipo => $cantidad)
                <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:text class="text-xs uppercase tracking-widest text-zinc-400">
                        {{ $tipo }}
                    </flux:text>
                    <p class="mt-2 text-4xl font-bold text-zinc-800 dark:text-white">
                        {{ $cantidad }}
                    </p>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts::app>