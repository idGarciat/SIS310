<?php

namespace App\Services;

class ProductionSystemService
{
    public function parseNumericList(string $value): array
    {
        $items = preg_split('/[\s,;\n\r]+/', trim($value)) ?: [];

        return array_values(array_map(
            static fn ($item) => (float) str_replace(',', '.', $item),
            array_filter($items, static fn ($item) => $item !== '')
        ));
    }

    public function parseTextList(string $value): array
    {
        $items = preg_split('/[\n,;]+/', trim($value)) ?: [];

        return array_values(array_map(
            static fn ($item) => trim($item),
            array_filter($items, static fn ($item) => trim($item) !== '')
        ));
    }

    public function calculatePronosticos(string $method, array $demand, ?int $n, ?string $weightsText, ?int $horizon, ?float $alpha, ?float $beta, ?int $seasonLength): array
    {
        return match ($method) {
            'promediomovilsimple' => [
                'method' => 'Promedio movil simple',
                'series' => $this->movingAverageSimple($demand, $n ?? 2),
                'summary' => $this->summarizeSeries($demand, $this->movingAverageSimple($demand, $n ?? 2)),
            ],
            'promediomovilponderado' => [
                'method' => 'Promedio movil ponderado',
                'series' => $this->weightedMovingAverage($demand, $this->parseNumericList($weightsText ?? ''), $n ?? 2, $horizon ?? 0),
            ],
            'regresionlineal' => [
                'method' => 'Regresion lineal',
                'series' => $this->linearRegressionSeasonal($demand, $seasonLength ?? 2, $horizon ?? 0),
            ],
            'suavisadoexponencialsimple' => [
                'method' => 'Suavizado exponencial simple',
                'series' => $this->exponentialSmoothingSimple($demand, $alpha ?? 0.5),
                'summary' => $this->summarizeSeries($demand, $this->exponentialSmoothingSimple($demand, $alpha ?? 0.5)),
            ],
            'suavisadoexponencialdoble' => [
                'method' => 'Suavizado exponencial doble',
                'series' => $this->doubleExponentialSmoothing($demand, $alpha ?? 0.5, $beta ?? 0.3, $horizon ?? 1),
                'summary' => $this->summarizeSeries($demand, $this->doubleExponentialSmoothing($demand, $alpha ?? 0.5, $beta ?? 0.3, $horizon ?? 1)),
            ],
            default => [
                'method' => 'Metodo no reconocido',
                'series' => [],
            ],
        };
    }

    public function movingAverageSimple(array $demand, int $window): array
    {
        $window = max(2, $window);
        $forecast = array_fill(0, $window - 1, null);

        for ($index = $window - 1; $index < count($demand); $index++) {
            $sum = 0;

            for ($offset = 0; $offset < $window; $offset++) {
                $sum += $demand[$index - $offset];
            }

            $forecast[] = round($sum / $window, 2);
        }

        return $forecast;
    }

    public function weightedMovingAverage(array $demand, array $weights, int $window, int $horizon): array
    {
        $window = max(1, $window);
        $weights = array_values($weights);
        $forecast = [];

        for ($index = $window - 1; $index < count($demand); $index++) {
            $sum = 0;

            for ($offset = 0; $offset < $window; $offset++) {
                $sum += ($demand[$index - $offset] ?? 0) * ($weights[$offset] ?? 0);
            }

            $forecast[] = round($sum, 2);
        }

        for ($step = 0; $step < $horizon; $step++) {
            $sum = 0;
            $baseIndex = count($demand) - 1;

            for ($offset = 0; $offset < $window; $offset++) {
                $sum += ($demand[$baseIndex - $offset] ?? end($demand) ?? 0) * ($weights[$offset] ?? 0);
            }

            $forecast[] = round($sum, 2);
            $demand[] = $sum;
        }

        return $forecast;
    }

    public function exponentialSmoothingSimple(array $demand, float $alpha): array
    {
        if (count($demand) < 2) {
            return $demand;
        }

        $alpha = max(0, min(1, $alpha));
        $forecast = [$demand[0], round(($demand[0] + $demand[1]) / 2, 2)];

        for ($index = 2; $index <= count($demand); $index++) {
            $forecast[$index] = round($alpha * ($demand[$index - 1] - $forecast[$index - 1]) + $forecast[$index - 1], 2);
        }

        return $forecast;
    }

    public function doubleExponentialSmoothing(array $demand, float $alpha, float $beta, int $horizon): array
    {
        if (count($demand) < 2) {
            return $demand;
        }

        $alpha = max(0, min(1, $alpha));
        $beta = max(0, min(1, $beta));
        $trend = [0];
        $level = [$demand[0]];

        for ($index = 1; $index < count($demand); $index++) {
            $level[$index] = $alpha * $demand[$index] + (1 - $alpha) * ($level[$index - 1] + $trend[$index - 1]);
            $trend[$index] = $beta * ($level[$index] - $level[$index - 1]) + (1 - $beta) * $trend[$index - 1];
        }

        $forecast = [null];

        for ($index = 1; $index <= count($demand); $index++) {
            $forecast[$index] = round($level[$index - 1] + $trend[$index - 1], 2);
        }

        for ($index = count($demand) + 1; $index < count($demand) + $horizon; $index++) {
            $forecast[$index] = round(($forecast[$index - 1] ?? 0) + $trend[count($demand) - 1], 2);
        }

        return $forecast;
    }

    public function linearRegressionSeasonal(array $demand, int $seasonLength, int $horizon): array
    {
        $count = count($demand);

        if ($count === 0) {
            return [];
        }

        $sumX = 0;
        $sumY = 0;
        $sumXX = 0;
        $sumXY = 0;

        for ($index = 0; $index < $count; $index++) {
            $x = $index + 1;
            $y = $demand[$index];
            $sumX += $x;
            $sumY += $y;
            $sumXX += $x * $x;
            $sumXY += $x * $y;
        }

        $denominator = ($count * $sumXX - $sumX * $sumX);
        $m = $denominator === 0 ? 0 : (($count * $sumXY - $sumX * $sumY) / $denominator);
        $b = $count === 0 ? 0 : (($sumY / $count) - (($m * $sumX) / $count));

        $forecast = [];

        for ($index = 1; $index <= $count + $horizon; $index++) {
            $forecast[] = $m * $index + $b;
        }

        $seasonLength = max(1, min($seasonLength, max(1, intdiv($count, 2))));
        $seasonalFactors = [];

        for ($index = 0; $index < $seasonLength && ($index + $seasonLength) < $count; $index++) {
            $base = $forecast[$index] ?? 1;
            $next = $forecast[$index + $seasonLength] ?? 1;
            $seasonalFactors[] = (($demand[$index] / ($base ?: 1)) + ($demand[$index + $seasonLength] / ($next ?: 1))) / 2;
        }

        if ($seasonalFactors === []) {
            $seasonalFactors = [1];
        }

        $adjusted = [];

        foreach ($forecast as $index => $value) {
            $adjusted[] = $value * $seasonalFactors[$index % count($seasonalFactors)];
        }

        return [
            'trend' => ['m' => round($m, 4), 'b' => round($b, 4)],
            'forecast' => $forecast,
            'seasonalFactors' => $seasonalFactors,
            'adjustedForecast' => $adjusted,
            'summary' => $this->summarizeSeries($demand, $adjusted),
        ];
    }

    public function calculateIndicators(array $names, array $ingresos, array $costosProduccion, array $costosFijos, array $costosAdministrativos): array
    {
        $count = min(count($names), count($ingresos), count($costosProduccion), count($costosFijos), count($costosAdministrativos));
        $items = [];

        for ($index = 0; $index < $count; $index++) {
            $costoVariable = $costosProduccion[$index] - $costosFijos[$index];
            $utilidad = $ingresos[$index] - $costosProduccion[$index] - $costosFijos[$index] - $costosAdministrativos[$index];
            $rentabilidad = $ingresos[$index] == 0 ? 0 : ($utilidad / $ingresos[$index]) * 100;
            $indiceComercial = array_sum($ingresos) == 0 ? 0 : ($ingresos[$index] / array_sum($ingresos)) * 100;
            $contribucionUtilidad = $ingresos[$index] == 0 ? 0 : (($ingresos[$index] - $costoVariable) / $ingresos[$index]) * 100;

            $items[] = [
                'nombre' => $names[$index],
                'ingresos' => $ingresos[$index],
                'costosProduccion' => $costosProduccion[$index],
                'costosFijos' => $costosFijos[$index],
                'costosAdministrativos' => $costosAdministrativos[$index],
                'costosVariables' => $costoVariable,
                'utilidad' => $utilidad,
                'rentabilidadVentas' => $rentabilidad,
                'indiceComercial' => $indiceComercial,
                'contribucionUtilidad' => $contribucionUtilidad,
            ];
        }

        return [
            'items' => $items,
            'totales' => [
                'ingresos' => array_sum(array_column($items, 'ingresos')),
                'costosProduccion' => array_sum(array_column($items, 'costosProduccion')),
                'costosFijos' => array_sum(array_column($items, 'costosFijos')),
                'costosAdministrativos' => array_sum(array_column($items, 'costosAdministrativos')),
                'costosVariables' => array_sum(array_column($items, 'costosVariables')),
                'utilidad' => array_sum(array_column($items, 'utilidad')),
            ],
        ];
    }

    public function calculatePareto(array $names, array $units, array $prices): array
    {
        $count = min(count($names), count($units), count($prices));
        $items = [];

        for ($index = 0; $index < $count; $index++) {
            $items[] = [
                'nombre' => $names[$index],
                'unidades' => $units[$index],
                'precio' => $prices[$index],
                'ingreso' => $units[$index] * $prices[$index],
            ];
        }

        usort($items, static fn ($left, $right) => $right['ingreso'] <=> $left['ingreso']);

        $totalIngresos = array_sum(array_column($items, 'ingreso'));
        $acumulado = 0;

        foreach ($items as $index => $item) {
            $porcentaje = $totalIngresos == 0 ? 0 : round(($item['ingreso'] / $totalIngresos) * 100, 2);
            $acumulado += $porcentaje;
            $items[$index]['porcentaje'] = $porcentaje;
            $items[$index]['porcentajeAcumulado'] = round($acumulado, 2);
            $items[$index]['ingresoAcumulado'] = round(array_sum(array_column(array_slice($items, 0, $index + 1), 'ingreso')), 2);
        }

        return [
            'items' => $items,
            'totales' => [
                'ingresos' => $totalIngresos,
                'porcentaje' => array_sum(array_column($items, 'porcentaje')),
            ],
        ];
    }

    public function calculatePap(int $alternative, array $months, float $initialWorkers, float $hoursPerUnit, float $hoursPerDay, float $unitsProduced, float $productionCost, float $hireCost, float $fireCost, float $overtimeCost, float $subcontractCost, float $storageCost, float $shortageCost, float $laborHourCost, array $demands, array $days): array
    {
        $count = min(count($months), count($demands), count($days));
        $months = array_slice($months, 0, $count);
        $demands = array_slice($demands, 0, $count);
        $days = array_slice($days, 0, $count);

        $unitsPerWorker = [];
        $requiredWorkers = [];
        $availableWorkers = [$initialWorkers];
        $hired = [];
        $fired = [];
        $workers = [];
        $production = [];
        $inventory = [0];
        $inventoryLevels = [];
        $storageCosts = [];
        $shortageCosts = [];
        $laborCosts = [];
        $overtime = [];
        $overtimeCosts = [];
        $subcontractedUnits = [];
        $subcontractingCosts = [];
        $productionUnitCosts = [];

        for ($index = 0; $index < $count; $index++) {
            $unitsPerWorker[$index] = round($days[$index] * $unitsProduced, 2);
            $requiredWorkers[$index] = max(1, (int) ceil($demands[$index] / max(1, $unitsPerWorker[$index])));
        }

        if ($alternative === 2 || $alternative === 5) {
            $stableWorkers = ceil(array_sum($demands) / max(1, array_sum($unitsPerWorker)));
            foreach ($requiredWorkers as $index => $value) {
                $requiredWorkers[$index] = (int) max(1, $stableWorkers);
            }
        }

        if ($alternative === 3) {
            $smallestDemandIndex = array_search(min($demands), $demands, true);
            $stableWorkers = ceil($demands[$smallestDemandIndex] / max(1, $unitsPerWorker[$smallestDemandIndex]));
            foreach ($requiredWorkers as $index => $value) {
                $requiredWorkers[$index] = (int) max(1, $stableWorkers);
            }
        }

        for ($index = 0; $index < $count; $index++) {
            $currentAvailable = $availableWorkers[$index];

            if ($currentAvailable < $requiredWorkers[$index]) {
                $hired[$index] = $requiredWorkers[$index] - $currentAvailable;
                $fired[$index] = 0;
            } elseif ($currentAvailable > $requiredWorkers[$index]) {
                $hired[$index] = 0;
                $fired[$index] = $currentAvailable - $requiredWorkers[$index];
            } else {
                $hired[$index] = 0;
                $fired[$index] = 0;
            }

            $availableWorkers[$index + 1] = $requiredWorkers[$index];
            $workers[$index] = $requiredWorkers[$index];

            if ($alternative === 1) {
                $production[$index] = $demands[$index];
                $overtime[$index] = 0;
                $subcontractedUnits[$index] = 0;
            } elseif ($alternative === 4) {
                $production[$index] = $unitsPerWorker[$index] * $workers[$index];
                $overtime[$index] = 0;
                $subcontractedUnits[$index] = 0;
            } elseif ($alternative === 5) {
                $production[$index] = $demands[$index];
                $overtime[$index] = 0;
                $subcontractedUnits[$index] = 0;
            } else {
                $production[$index] = $unitsPerWorker[$index] * $workers[$index];
                $overtime[$index] = 0;
                $subcontractedUnits[$index] = 0;
            }

            $inventory[$index + 1] = ($production[$index] ?? 0) - $demands[$index] + ($inventory[$index] ?? 0);
        }

        $inventoryLevels = array_slice($inventory, 1);

        foreach ($inventoryLevels as $index => $level) {
            if ($level < 0 && $alternative === 3) {
                $subcontractedUnits[$index] = abs($level);
                $inventoryLevels[$index] = 0;
            }

            if ($inventoryLevels[$index] < 0) {
                $storageCosts[$index] = 0;
                $shortageCosts[$index] = abs($inventoryLevels[$index]) * $shortageCost;
            } else {
                $storageCosts[$index] = $inventoryLevels[$index] * $storageCost;
                $shortageCosts[$index] = 0;
            }

            if ($alternative === 4) {
                $laborCosts[$index] = $demands[$index] * $hoursPerUnit * $laborHourCost;
            } else {
                $laborCosts[$index] = $days[$index] * $laborHourCost * $hoursPerDay * ($workers[$index] ?? 0);
            }

            if ($alternative === 5) {
                $normalHoursNeeded = $demands[$index] * $hoursPerUnit;
                $normalHoursAvailable = ($unitsPerWorker[$index] * ($workers[$index] ?? 0)) * $hoursPerUnit;
                $overtime[$index] = max(0, $normalHoursNeeded - $normalHoursAvailable);
            }

            $overtimeCosts[$index] = ($overtime[$index] ?? 0) * $overtimeCost;
            $subcontractingCosts[$index] = ($subcontractedUnits[$index] ?? 0) * $subcontractCost;
            $productionUnitCosts[$index] = ($production[$index] ?? 0) * $productionCost;
        }

        $totals = [
            'Dias Totales' => array_sum($days),
            'Unidades Totales por trabajador' => array_sum($unitsPerWorker),
            'Demanda Total' => array_sum($demands),
            'Costo Total de Contratación' => array_sum(array_map(static fn ($value) => $value * $hireCost, $hired)),
            'Costo Total de Despido' => array_sum(array_map(static fn ($value) => $value * $fireCost, $fired)),
            'Costo Total de Mano de Obra' => array_sum($laborCosts),
            'Costo Total de Horas Extras' => array_sum($overtimeCosts),
            'Costo Total de Subcontratación' => array_sum($subcontractingCosts),
            'Costo Total de Almacenamiento' => array_sum($storageCosts),
            'Costo Total de Escasez' => array_sum($shortageCosts),
            'Costo Total de Producción' => array_sum($productionUnitCosts),
        ];

        $totals['Costo Total General'] = array_sum($totals);

        return [
            'months' => $months,
            'demand' => $demands,
            'days' => $days,
            'unitsPerWorker' => $unitsPerWorker,
            'requiredWorkers' => $requiredWorkers,
            'workers' => $workers,
            'hired' => $hired,
            'fired' => $fired,
            'production' => $production,
            'inventory' => $inventoryLevels,
            'storageCosts' => $storageCosts,
            'shortageCosts' => $shortageCosts,
            'laborCosts' => $laborCosts,
            'overtimeCosts' => $overtimeCosts,
            'subcontractingCosts' => $subcontractingCosts,
            'productionUnitCosts' => $productionUnitCosts,
            'totals' => $totals,
        ];
    }

    private function summarizeSeries(array $demand, array $forecast): array
    {
        $errors = [];
        $absoluteErrors = [];
        $percentageErrors = [];
        $squaredErrors = [];

        $limit = min(count($demand), count($forecast));

        for ($index = 0; $index < $limit; $index++) {
            if (!array_key_exists($index, $forecast) || $forecast[$index] === null) {
                continue;
            }

            $error = $demand[$index] - $forecast[$index];
            $errors[] = $error;
            $absoluteErrors[] = abs($error);
            $percentageErrors[] = $demand[$index] == 0 ? null : ($error / $demand[$index]) * 100;
            $squaredErrors[] = $error ** 2;
        }

        $filteredPercentages = array_values(array_filter($percentageErrors, static fn ($value) => $value !== null));

        return [
            'errors' => $errors,
            'absoluteErrors' => $absoluteErrors,
            'percentageErrors' => $percentageErrors,
            'squaredErrors' => $squaredErrors,
            'mad' => $absoluteErrors === [] ? null : array_sum($absoluteErrors) / count($absoluteErrors),
            'mse' => $squaredErrors === [] ? null : array_sum($squaredErrors) / count($squaredErrors),
            'mape' => $filteredPercentages === [] ? null : array_sum(array_map(static fn ($value) => abs((float) $value), $filteredPercentages)) / count($filteredPercentages),
        ];
    }
}
