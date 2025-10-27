<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReportesResource;
use App\Filament\Resources\ReportesResource\Pages\ListReportes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class reservasWidget extends BaseWidget
{
   use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListReportes::class;
    }

    protected function getStats(): array
    {
        /** @var EloquentBuilder $base */
        $base = $this->getPageTableQuery();

        // Conteos por estado (respetan tabs/filtros/búsqueda actuales)
        $aprobadas  = (clone $base)->where('estado', 'aprobado')->count();
        $pendientes = (clone $base)->where('estado', 'pendiente')->count();
        $canceladas = (clone $base)->where('estado', 'cancelado')->count();
        $rechazadas = (clone $base)->where('estado', 'rechazado')->count();
        $total      = $aprobadas + $pendientes + $canceladas + $rechazadas;

        // 🔹 Series mensuales por estado (todo el histórico)
        $apSeries = $this->monthlySeriesAllTime((clone $base)->where('estado', 'aprobado'));
        $peSeries = $this->monthlySeriesAllTime((clone $base)->where('estado', 'pendiente'));
        $caSeries = $this->monthlySeriesAllTime((clone $base)->where('estado', 'cancelado'));
        $reSeries = $this->monthlySeriesAllTime((clone $base)->where('estado', 'rechazado'));

        // Serie total = suma punto a punto
        $maxLen = max(count($apSeries), count($peSeries), count($caSeries), count($reSeries));
        $pad = function(array $s) use ($maxLen) {
            return array_pad($s, $maxLen, 0);
        };
        $totalSeries = [];
        $A = $pad($apSeries); $P = $pad($peSeries); $C = $pad($caSeries); $R = $pad($reSeries);
        for ($i = 0; $i < $maxLen; $i++) {
            $totalSeries[] = ($A[$i] ?? 0) + ($P[$i] ?? 0) + ($C[$i] ?? 0) + ($R[$i] ?? 0);
        }

        // Nota: si alguna serie queda vacía, le ponemos un par de ceros para que Filament muestre la sparkline
        $ensure = fn(array $s) => count($s) >= 2 ? $s : [0, 0];

        return [
            Stat::make('Aprobadas', $aprobadas)
            ->color('success')
            ->chart($ensure($apSeries))
            ->icon('heroicon-o-check-circle')
            ->description('Reservas aprobadas')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->descriptionColor('success')
            ->color('success'),
            Stat::make('Pendientes', $pendientes)
            ->color('warning')
            ->chart($ensure($peSeries))
            ->icon('heroicon-o-clock')
            ->description('Reservas pendientes')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->descriptionColor('warning')
            ->color('warning'),
            Stat::make('Canceladas', $canceladas)
            ->color('gray')
            ->chart($ensure($caSeries))
            ->icon('heroicon-o-x-circle')
            ->description('Reservas canceladas')
            ->descriptionIcon('heroicon-m-arrow-trending-down')
            ->color('gray'),
            Stat::make('Rechazadas', $rechazadas)
            ->color('danger')
            ->chart($ensure($reSeries))
            ->icon('heroicon-o-no-symbol')
            ->description('Reservas rechazadas')
            ->descriptionIcon('heroicon-m-arrow-trending-down')
            ->descriptionColor('danger')
            ->color('danger'),
            Stat::make('Total de Reservas', $total)
            ->color('primary')
            ->chart($ensure($totalSeries))
            ->icon('heroicon-o-chart-bar')
            ->description('Total de reservas')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->descriptionColor('primary')
            ->color('primary'),
        ];
    }

    /**
     * Serie mensual continua desde el primer registro hasta el mes actual.
     * Respeta filtros/tabs/búsqueda del builder recibido.
     */
    private function monthlySeriesAllTime(EloquentBuilder $query): array
    {
        $first = (clone $query)->reorder()->min('created_at');
        if (!$first) {
            return [];
        }

        $start = Carbon::parse($first)->startOfMonth();
        $end   = Carbon::now()->startOfMonth();

        $rows = (clone $query)
            ->reorder() // limpia ORDER BY heredado
            ->selectRaw("date_trunc('month', created_at)::date as m, count(*)::int as v")
            ->groupBy('m')
            ->orderBy('m')
            ->get()
            ->keyBy(fn ($r) => (string) $r->m);

        $series = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $series[] = isset($rows[$key]) ? (int) $rows[$key]->v : 0;
            $cursor->addMonth();
        }

        return $series;
    }
}