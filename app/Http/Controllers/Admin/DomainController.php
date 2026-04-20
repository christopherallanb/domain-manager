<?php

namespace App\Http\Controllers\Admin;

use App\Models\Domain;
use App\Http\Controllers\Controller;
use App\Http\Requests\Domain\StoreDomainRequest;
use App\Http\Requests\Domain\UpdateDomainRequest;
use App\Http\Requests\Domain\RenewDomainRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = request('q');
        $filter = request('filter'); // 'in30', 'in7', 'expired' or null
        $sort = request('sort', 'expiration_date');
        $direction = request('dir', 'asc');

        $query = Domain::query();

        if ($q) {
            $query->where(function($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")->orWhere('registrar', 'like', "%{$q}%");
            });
        }

        if ($filter === 'in30') {
            $query->whereBetween('expiration_date', [now(), now()->addDays(30)]);
        } elseif ($filter === 'in7') {
            $query->whereBetween('expiration_date', [now(), now()->addDays(7)]);
        } elseif ($filter === 'expired') {
            $query->where('expiration_date', '<', now());
        }

        $allowedSorts = ['expiration_date', 'name', 'annual_cost', 'registrar'];
        if (!in_array($sort, $allowedSorts)) $sort = 'expiration_date';
        $direction = $direction === 'desc' ? 'desc' : 'asc';

        $domains = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return view('admin.domains.index', compact('domains'));
    }

    /**
     * Show the dashboard with statistics.
     */
    public function dashboard()
    {
        $domains = Domain::all();
        return view('admin.domains.dashboard', compact('domains'));
    }

    /**
     * Show the reports page.
     */
    public function reports()
    {
        $year = request('year');
        $query = Domain::query();
        if ($year) {
            $query->whereYear('expiration_date', $year);
        }
        $domains = $query->get();
        return view('admin.domains.reports', compact('domains'));
    }

    /**
     * Show the settings page.
     */
    public function settings()
    {
        // Try to read from settings table if exists, otherwise from env
        $settings = [];
        if (Schema::hasTable('settings')) {
            $settings = \DB::table('settings')->pluck('value','key')->toArray();
        }
        return view('admin.domains.settings', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.domains.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDomainRequest $request)
    {
        $validated = $request->validated();
        Domain::create($validated);

        return redirect()->route('domains.index')
            ->with('success', 'Domínio cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Domain $domain)
    {
        return view('admin.domains.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Domain $domain)
    {
        return view('admin.domains.edit', compact('domain'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDomainRequest $request, Domain $domain)
    {
        $validated = $request->validated();
        $domain->update($validated);

        return redirect()->route('domains.index')
            ->with('success', 'Domínio atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return redirect()->route('domains.index')
            ->with('success', 'Domínio excluído com sucesso!');
    }

    /**
     * Renew a domain.
     */
    public function renew(RenewDomainRequest $request, Domain $domain)
    {
        $validated = $request->validated();
        $domain->update([
            'expiration_date' => $validated['new_date'],
        ]);

        return redirect()->back()
            ->with('success', 'Domínio renovado com sucesso!');
    }

    /** Import CSV */
    public function importCsv(Request $request)
    {
        $request->validate([ 'file' => 'required|file|mimes:csv,txt' ]);
        $path = $request->file('file')->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));
        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            Domain::updateOrCreate(['name' => $data['name']], [
                'registrar' => $data['registrar'] ?? null,
                'expiration_date' => $data['expiration_date'] ?? null,
                'annual_cost' => $data['annual_cost'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'auto_renew' => isset($data['auto_renew']) ? (bool)$data['auto_renew'] : false,
            ]);
        }
        return redirect()->back()->with('success', 'CSV importado.');
    }

    /** Export domains CSV */
    public function exportCsv()
    {
        $domains = Domain::all();
        $filename = 'domains_export_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        $callback = function() use ($domains) {
            $out = fopen('php://output','w');
            fputcsv($out, ['name','registrar','expiration_date','annual_cost','notes','auto_renew']);
            foreach ($domains as $d) {
                fputcsv($out, [$d->name,$d->registrar,$d->expiration_date->format('Y-m-d'),$d->annual_cost,$d->notes,$d->auto_renew]);
            }
            fclose($out);
        };
        return response()->stream($callback, 200, $headers);
    }

    /** Export reports CSV */
    public function exportReports()
    {
        $year = request('year');
        $query = Domain::query();
        if ($year) $query->whereYear('expiration_date', $year);
        $byRegistrar = $query->get()->groupBy('registrar')->map(function($g){
            return ['count' => $g->count(), 'cost' => $g->sum('annual_cost')];
        });

        $filename = 'reports_'.($year?:'all').'_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($byRegistrar) {
            $out = fopen('php://output','w');
            fputcsv($out, ['registrar','domains_count','total_cost']);
            foreach ($byRegistrar as $registrar => $data) {
                fputcsv($out, [$registrar, $data['count'], $data['cost']]);
            }
            fclose($out);
        };
        return response()->stream($callback, 200, $headers);
    }

    /** Save settings (simple table) */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'alert_emails' => 'nullable|string',
            'alert_days' => 'nullable|string',
        ]);
        if (!Schema::hasTable('settings')) {
            // create table migration-like quick store
            Schema::create('settings', function($table){
                $table->string('key')->primary();
                $table->text('value')->nullable();
            });
        }
        \DB::table('settings')->updateOrInsert(['key'=>'alert_emails'], ['value' => $request->input('alert_emails')]);
        \DB::table('settings')->updateOrInsert(['key'=>'alert_days'], ['value' => $request->input('alert_days')]);

        return redirect()->back()->with('success', 'Configurações salvas.');
    }
}
