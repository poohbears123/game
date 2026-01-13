<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Games Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Games Report</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Release Year</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            @foreach($games as $game)
                <tr>
                    <td>{{ $game->title }}</td>
                    <td>{{ $game->description }}</td>
                    <td>{{ $game->release_year }}</td>
                    <td>{{ $game->category ? $game->category->name : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
