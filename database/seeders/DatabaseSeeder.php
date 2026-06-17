<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Calculation;
use App\Services\ProductionSystemService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => 'admin123',
                'is_admin' => true,
            ]
        );

        $pruebasUser = User::query()->updateOrCreate(
            ['email' => 'Pruebas@gmail.com'],
            [
                'name' => 'Usuario Pruebas',
                'password' => 'pruebas123',
                'is_admin' => false,
            ]
        );

        // Delete previous calculations for this user if they exist to avoid duplication
        Calculation::where('user_id', $pruebasUser->id)->delete();

        $service = new ProductionSystemService();

        // 1. Pronósticos
        $metodosPronosticos = [
            [
                'metodo' => 'promediomovilsimple',
                'demanda' => '100, 120, 110, 130, 140, 150',
                'n' => 3,
                'pesos' => null,
                'alcance' => null,
                'alpha' => null,
                'beta' => null,
                'proporcion' => null
            ],
            [
                'metodo' => 'promediomovilponderado',
                'demanda' => '100, 120, 110, 130, 140, 150',
                'n' => 3,
                'pesos' => '0.5, 0.3, 0.2',
                'alcance' => 2,
                'alpha' => null,
                'beta' => null,
                'proporcion' => null
            ],
            [
                'metodo' => 'regresionlineal',
                'demanda' => '100, 120, 110, 130, 140, 150',
                'n' => null,
                'pesos' => null,
                'alcance' => 2,
                'alpha' => null,
                'beta' => null,
                'proporcion' => 3
            ],
            [
                'metodo' => 'suavisadoexponencialsimple',
                'demanda' => '100, 120, 110, 130, 140, 150',
                'n' => null,
                'pesos' => null,
                'alcance' => null,
                'alpha' => 0.3,
                'beta' => null,
                'proporcion' => null
            ],
            [
                'metodo' => 'suavisadoexponencialdoble',
                'demanda' => '100, 120, 110, 130, 140, 150',
                'n' => null,
                'pesos' => null,
                'alcance' => 2,
                'alpha' => 0.3,
                'beta' => 0.2,
                'proporcion' => null
            ]
        ];

        foreach ($metodosPronosticos as $inputPronosticos) {
            $demandas = $service->parseNumericList($inputPronosticos['demanda']);
            $resultPronosticos = $service->calculatePronosticos(
                $inputPronosticos['metodo'],
                $demandas,
                $inputPronosticos['n'],
                $inputPronosticos['pesos'],
                $inputPronosticos['alcance'],
                $inputPronosticos['alpha'],
                $inputPronosticos['beta'],
                $inputPronosticos['proporcion']
            );
            Calculation::create([
                'user_id' => $pruebasUser->id,
                'module' => 'pronosticos',
                'input_data' => $inputPronosticos,
                'result_data' => $resultPronosticos
            ]);
        }

        // 2. Indicadores
        $inputIndicadores = [
            'nombresProducto' => "Producto A, Producto B,   Producto C",
            'ingresos' => '5000, 3000, 2000',
            'costosProduccion' => '3000, 1800, 1200',
            'costosFijos' => '1000, 600, 400',
            'costosAdministrativos' => '250, 320, 120',
        ];
        $resultIndicadores = $service->calculateIndicators(
            $service->parseTextList($inputIndicadores['nombresProducto']),
            $service->parseNumericList($inputIndicadores['ingresos']),
            $service->parseNumericList($inputIndicadores['costosProduccion']),
            $service->parseNumericList($inputIndicadores['costosFijos']),
            $service->parseNumericList($inputIndicadores['costosAdministrativos'])
        );
        Calculation::create([
            'user_id' => $pruebasUser->id,
            'module' => 'indicadores',
            'input_data' => $inputIndicadores,
            'result_data' => $resultIndicadores
        ]);

        // 3. Pareto
        $inputPareto = [
            'nombreProductos' => "Tornillos\nClavos\nTuercas\nArandelas",
            'unidades' => '1000, 2000, 1500, 5000',
            'precios' => '0.5, 0.3, 0.6, 0.1',
        ];
        $resultPareto = $service->calculatePareto(
            $service->parseTextList($inputPareto['nombreProductos']),
            $service->parseNumericList($inputPareto['unidades']),
            $service->parseNumericList($inputPareto['precios'])
        );
        Calculation::create([
            'user_id' => $pruebasUser->id,
            'module' => 'pareto',
            'input_data' => $inputPareto,
            'result_data' => $resultPareto
        ]);

        // 4. PAP (Plan Agregado de Producción)
        for ($alt = 1; $alt <= 5; $alt++) {
            $inputPap = [
                'alternativasPAP' => $alt,
                'mesesTotal' => "Ene,Feb,Mar,Abr,May,Jun",
                'trabIniciales' => '8',
                'horasUnidad' => '4',
                'horasDia' => '8',
                'unidadesProducidas' => '2',
                'costoProduccion' => '50',
                'costoContratacion' => '100',
                'costoDespido' => '150',
                'costoHoraExtra' => '12',
                'costoSubcontratacion' => '75',
                'costoAlmacenamiento' => '3',
                'costoRotura' => '10',
                'costoMOhr' => '6',
                'demandas' => '1000,1200,1500,1300,1400,1600',
                'dias' => '22,20,23,21,22,20',
            ];
            $resultPap = $service->calculatePap(
                (int) $inputPap['alternativasPAP'],
                $service->parseTextList($inputPap['mesesTotal']),
                $service->parseNumericList($inputPap['trabIniciales'])[0] ?? 0,
                $service->parseNumericList($inputPap['horasUnidad'])[0] ?? 0,
                $service->parseNumericList($inputPap['horasDia'])[0] ?? 0,
                $service->parseNumericList($inputPap['unidadesProducidas'])[0] ?? 0,
                $service->parseNumericList($inputPap['costoProduccion'])[0] ?? 0,
                $service->parseNumericList($inputPap['costoContratacion'])[0] ?? 0,
                $service->parseNumericList($inputPap['costoDespido'])[0] ?? 0,
                $service->parseNumericList($inputPap['costoHoraExtra'])[0] ?? 0,
                $service->parseNumericList($inputPap['costoSubcontratacion'])[0] ?? 0,
                $service->parseNumericList($inputPap['costoAlmacenamiento'])[0] ?? 0,
                $service->parseNumericList($inputPap['costoRotura'])[0] ?? 0,
                $service->parseNumericList($inputPap['costoMOhr'])[0] ?? 0,
                $service->parseNumericList($inputPap['demandas']),
                $service->parseNumericList($inputPap['dias'])
            );
            Calculation::create([
                'user_id' => $pruebasUser->id,
                'module' => 'pap',
                'input_data' => $inputPap,
                'result_data' => $resultPap
            ]);
        }
    }
}
