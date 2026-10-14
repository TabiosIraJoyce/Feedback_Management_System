<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        h1 { font-size: 20px; margin-bottom: 6px; }
        p { margin: 0 0 4px; color: #475569; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #e2e8f0; font-size: 11px; text-transform: uppercase; }
        .meta { margin-bottom: 12px; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="meta">
        <p>Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Filters: {{ collect($filters)->filter()->map(fn ($value, $key) => $key.': '.$value)->implode(' | ') ?: 'None' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($columns as $column)
                        <td>{{ $row[$column['key']] ?? '' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}">No records found for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
