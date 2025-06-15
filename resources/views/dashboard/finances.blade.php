@extends('layouts.dashboard')

@section('title', 'Finanzas')

@push('styles')
<style>
    .revenue-card {
        border-left: 4px solid #198754;
    }
    .expense-card {
        border-left: 4px solid #dc3545;
    }
    .budget-card {
        border-left: 4px solid #0d6efd;
    }
    .transaction-income {
        border-left: 3px solid #198754;
    }
    .transaction-expense {
        border-left: 3px solid #dc3545;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Finanzas</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Exportar</button>
        </div>
        <button type="button" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Añadir Transacción
        </button>
    </div>
</div>

<!-- Resumen Financiero -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card h-100 revenue-card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Ingresos (Mes Actual)</h6>
                <h3 class="mb-0">@money(array_sum($finances['revenue']))</h3>
                <div class="mt-2">
                    <span class="text-success">
                        <i class="bi bi-arrow-up"></i> 12.5%
                    </span>
                    <span class="text-muted">respecto al mes anterior</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 expese-card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Gastos (Mes Actual)</h6>
                <h3 class="mb-0">@money(array_sum($finances['expenses']))</h3>
                <div class="mt-2">
                    <span class="text-danger">
                        <i class="bi bi-arrow-down"></i> 3.2%
                    </span>
                    <span class="text-muted">respecto al mes anterior</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100 budget-card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Balance Actual</h6>
                <h3 class="mb-0">@money($finances['balance'])</h3>
                <div class="mt-2">
                    <span class="text-success">
                        <i class="bi bi-graph-up"></i> 5.8%
                    </span>
                    <span class="text-muted">respecto al mes anterior</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Gráfico de Ingresos vs Gastos -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title mb-0">Ingresos vs Gastos (Últimos 6 meses)</h6>
            </div>
            <div class="card-body">
                <canvas id="incomeExpenseChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Distribución de Gastos -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="card-title mb-0">Distribución de Gastos</h6>
            </div>
            <div class="card-body">
                <canvas id="expenseDistributionChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Últimas Transacciones -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title mb-0">Últimas Transacciones</h6>
        <a href="#" class="btn btn-sm btn-outline-primary">Ver Todas</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th class="text-end">Monto</th>
                        <th class="text-end">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $runningBalance = $finances['balance'];
                        // Invertir el array para mostrar las transacciones más recientes primero
                        $recentTransactions = array_reverse($finances['transactions']);
                    @endphp
                    @foreach($recentTransactions as $transaction)
                        @php
                            $runningBalance -= $transaction['amount'];
                            $isIncome = $transaction['amount'] > 0;
                        @endphp
                        <tr class="transaction-{{ $isIncome ? 'income' : 'expense' }}">
                            <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('d/m/Y') }}</td>
                            <td>{{ $transaction['description'] }}</td>
                            <td>
                                <span class="badge bg-{{ $isIncome ? 'success' : 'danger' }} bg-opacity-10 text-{{ $isIncome ? 'success' : 'danger' }}">
                                    {{ ucfirst($transaction['type']) }}
                                </span>
                            </td>
                            <td class="text-end fw-bold {{ $isIncome ? 'text-success' : 'text-danger' }}">
                                {{ $isIncome ? '+' : '-' }} @money(abs($transaction['amount']))
                            </td>
                            <td class="text-end">@money($runningBalance)</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="4" class="text-end">Saldo Final:</th>
                        <th class="text-end">@money($finances['balance'])</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Ingresos vs Gastos
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
    new Chart(incomeExpenseCtx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Ingresos',
                    data: [1200000, 1900000, 1500000, 1800000, 2100000, 2350000],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Gastos',
                    data: [900000, 1200000, 1000000, 1400000, 1300000, 1500000],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(value);
                        }
                    }
                }
            }
        }
    });

    // Gráfico de distribución de gastos
    const expenseDistCtx = document.getElementById('expenseDistributionChart').getContext('2d');
    new Chart(expenseDistCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys($finances['expenses']).map(label => label.charAt(0).toUpperCase() + label.slice(1)),
            datasets: [{
                data: Object.values($finances['expenses']),
                backgroundColor: [
                    '#ff6384',
                    '#36a2eb',
                    '#ffce56',
                    '#4bc0c0',
                    '#9966ff',
                    '#ff9f40'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@endsection
