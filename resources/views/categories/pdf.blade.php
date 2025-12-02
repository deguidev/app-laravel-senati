<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Categorías</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #2563eb;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tr:hover {
            background-color: #f3f4f6;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        .stat-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Categorías</h1>
        <p>Generado el {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ count($categories) }}</div>
            <div class="stat-label">Total Categorías</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $categories->where('active', true)->count() }}</div>
            <div class="stat-label">Activas</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $categories->where('active', false)->count() }}</div>
            <div class="stat-label">Inactivas</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">ID</th>
                <th style="width: 25%;">Nombre</th>
                <th style="width: 42%;">Descripción</th>
                <th style="width: 12%;">Estado</th>
                <th style="width: 13%;">Fecha Creación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td>{{ $category->description ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $category->active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $category->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #9ca3af;">
                        No hay categorías registradas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema de Gestión de Categorías - Laravel SENATI</p>
        <p>Este documento fue generado automáticamente</p>
    </div>
</body>
</html>
