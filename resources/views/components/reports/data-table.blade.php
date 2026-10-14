@props([
    'columns' => [],
    'rows' => collect(),
    'emptyMessage' => 'No rows found for the selected filters.',
])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th class="px-4 py-3 text-left font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $column['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-900">
            @forelse ($rows as $row)
                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/60">
                    @foreach ($columns as $column)
                        <td class="px-4 py-3 text-slate-700 dark:text-slate-200">{{ $row[$column['key']] ?? '-' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">{{ $emptyMessage }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
