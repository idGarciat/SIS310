<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Services\ProductionSystemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemController extends Controller
{
    public function __construct(private readonly ProductionSystemService $service)
    {
    }

    private function getRecalledForm(Request $request): array
    {
        if ($request->has('recall')) {
            $calc = Calculation::where('id', $request->query('recall'))
                ->where('user_id', Auth::id())
                ->first();
            return $calc ? $calc->input_data : [];
        }
        return [];
    }

    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($validated, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors(['email' => 'Las credenciales no son correctas.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function pronosticos(Request $request)
    {
        $result = null;
        $form = $this->getRecalledForm($request);

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'metodo' => ['required', 'string'],
                'demanda' => ['required', 'string'],
                'n' => ['nullable', 'integer', 'min:2'],
                'pesos' => ['nullable', 'string'],
                'alcance' => ['nullable', 'integer', 'min:0'],
                'alpha' => ['nullable', 'numeric', 'between:0,1'],
                'beta' => ['nullable', 'numeric', 'between:0,1'],
                'proporcion' => ['nullable', 'integer', 'min:1'],
            ]);

            $demandas = $this->service->parseNumericList($validated['demanda']);
            $form = $validated;

            $result = $this->service->calculatePronosticos(
                $validated['metodo'],
                $demandas,
                $validated['n'] ?? null,
                $validated['pesos'] ?? null,
                $validated['alcance'] ?? null,
                $validated['alpha'] ?? null,
                $validated['beta'] ?? null,
                $validated['proporcion'] ?? null
            );

            if ($result) {
                Calculation::create([
                    'user_id' => Auth::id(),
                    'module' => 'pronosticos',
                    'input_data' => $validated,
                    'result_data' => $result
                ]);
            }
        }

        return view('modules.pronosticos', [
            'result' => $result,
            'form' => $form,
            'demandas' => $demandas ?? []
        ]);
    }

    public function indicadores(Request $request)
    {
        $result = null;
        $form = $this->getRecalledForm($request);

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'nombresProducto' => ['required', 'string'],
                'ingresos' => ['required', 'string'],
                'costosProduccion' => ['required', 'string'],
                'costosFijos' => ['required', 'string'],
                'costosAdministrativos' => ['required', 'string'],
            ]);

            $form = $validated;
            $result = $this->service->calculateIndicators(
                $this->service->parseTextList($validated['nombresProducto']),
                $this->service->parseNumericList($validated['ingresos']),
                $this->service->parseNumericList($validated['costosProduccion']),
                $this->service->parseNumericList($validated['costosFijos']),
                $this->service->parseNumericList($validated['costosAdministrativos'])
            );

            if ($result) {
                Calculation::create([
                    'user_id' => Auth::id(),
                    'module' => 'indicadores',
                    'input_data' => $validated,
                    'result_data' => $result
                ]);
            }
        }

        return view('modules.indicadores', compact('result', 'form'));
    }

    public function pareto(Request $request)
    {
        $result = null;
        $form = $this->getRecalledForm($request);

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'nombreProductos' => ['required', 'string'],
                'unidades' => ['required', 'string'],
                'precios' => ['required', 'string'],
            ]);

            $form = $validated;
            $result = $this->service->calculatePareto(
                $this->service->parseTextList($validated['nombreProductos']),
                $this->service->parseNumericList($validated['unidades']),
                $this->service->parseNumericList($validated['precios'])
            );

            if ($result) {
                Calculation::create([
                    'user_id' => Auth::id(),
                    'module' => 'pareto',
                    'input_data' => $validated,
                    'result_data' => $result
                ]);
            }
        }

        return view('modules.pareto', compact('result', 'form'));
    }

    public function pap(Request $request)
    {
        $result = null;
        $form = $this->getRecalledForm($request);

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'alternativasPAP' => ['required', 'integer', 'between:1,5'],
                'mesesTotal' => ['required', 'string'],
                'trabIniciales' => ['required', 'string'],
                'horasUnidad' => ['required', 'string'],
                'horasDia' => ['required', 'string'],
                'unidadesProducidas' => ['required', 'string'],
                'costoProduccion' => ['required', 'string'],
                'costoContratacion' => ['required', 'string'],
                'costoDespido' => ['required', 'string'],
                'costoHoraExtra' => ['required', 'string'],
                'costoSubcontratacion' => ['required', 'string'],
                'costoAlmacenamiento' => ['required', 'string'],
                'costoRotura' => ['required', 'string'],
                'costoMOhr' => ['required', 'string'],
                'demandas' => ['required', 'string'],
                'dias' => ['required', 'string'],
            ]);

            $form = $validated;
            $result = $this->service->calculatePap(
                (int) $validated['alternativasPAP'],
                $this->service->parseTextList($validated['mesesTotal']),
                $this->service->parseNumericList($validated['trabIniciales'])[0] ?? 0,
                $this->service->parseNumericList($validated['horasUnidad'])[0] ?? 0,
                $this->service->parseNumericList($validated['horasDia'])[0] ?? 0,
                $this->service->parseNumericList($validated['unidadesProducidas'])[0] ?? 0,
                $this->service->parseNumericList($validated['costoProduccion'])[0] ?? 0,
                $this->service->parseNumericList($validated['costoContratacion'])[0] ?? 0,
                $this->service->parseNumericList($validated['costoDespido'])[0] ?? 0,
                $this->service->parseNumericList($validated['costoHoraExtra'])[0] ?? 0,
                $this->service->parseNumericList($validated['costoSubcontratacion'])[0] ?? 0,
                $this->service->parseNumericList($validated['costoAlmacenamiento'])[0] ?? 0,
                $this->service->parseNumericList($validated['costoRotura'])[0] ?? 0,
                $this->service->parseNumericList($validated['costoMOhr'])[0] ?? 0,
                $this->service->parseNumericList($validated['demandas']),
                $this->service->parseNumericList($validated['dias'])
            );

            if ($result) {
                Calculation::create([
                    'user_id' => Auth::id(),
                    'module' => 'pap',
                    'input_data' => $validated,
                    'result_data' => $result
                ]);
            }
        }
        return view('modules.pap', compact('result', 'form'));
    }

    public function historial()
    {
        $calculations = Calculation::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('history', compact('calculations'));
    }
}
