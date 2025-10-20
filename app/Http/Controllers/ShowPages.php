<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShowPages extends Controller
{
    public function showdashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Login to access this page please');
        }

        $today_weight = Detail::whereBetween('created_at', [
            Carbon::now()->startOfDay(),
            Carbon::now()->endOfDay(),
        ])->count();
        $week_weight = Detail::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),   // Monday by default
            Carbon::now()->endOfWeek()
        ])->count();
        $month_weight = Detail::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])->count();
        $today_weight_by_user = Detail::where('created_by', Auth::user()->username)
            ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->count();

        $recent_weights = Detail::orderBy('created_at', 'desc')->take(5)->get();
        return view('pages.dashboard', [
            'today_weight' => $today_weight,
            'weekly_weight' => $week_weight,
            'monthly_weight' => $month_weight,
            'today_user' => $today_weight_by_user,
            'recent' => $recent_weights
        ]);
    }

    public function showfirst()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Login to add first weight');
        }

        $lastDetail = Detail::latest('id')->first();

        $serial = $lastDetail ? $lastDetail->id + 1 : 1;

        return view('pages.first', [
            'serial' => $serial,
        ]);
    }

    public function showsecond()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Please log in again to enter second weight');
        }
        return view('pages.second');
    }

    public function showprint()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Please login again to proceed');
        }
        return view('pages.print');
    }

    public function showprintlayout($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Not allowed to visit');
        }

        $record = Detail::where('id', '=', $id)->first();

        if (!$record) {
            return redirect()->route('print.record')->with('error', 'Please send the serial to that page');
        }

        $url = "https://kanta.malick.site/show/$record->id";
        $qrCode = QrCode::size(60)->generate($url);

        $user = User::where('username', '=', $record->created_by)->first();

        return view('pages.print-layout', compact('record', 'user', 'qrCode'));
    }

    public function addnewuser()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Please login to access that page again!');
        }
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Only admins are allowed to work here!');
        }

        return view('pages.add_user');
    }

    public function recordsPage()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Login to access this page');
        }

        return view('pages.monthly');
    }

    public function periodOptions(Request $request)
    {
        $this->authorizeAdmin();

        // Use first_date as the principal date anchor
        // DAYS
        $days = Detail::select(
            DB::raw("DATE(first_date) as day"),
            DB::raw("COUNT(*) as cnt")
        )
            ->whereNotNull('first_date')
            ->groupBy(DB::raw("DATE(first_date)"))
            ->orderBy(DB::raw("DATE(first_date)"), 'desc')
            ->limit(365) // safety cap
            ->get()
            ->map(function ($r) {
                return [
                    'value' => Carbon::parse($r->day)->format('Y-m-d'),
                    'label' => Carbon::parse($r->day)->format('d M Y'),
                    'count' => (int) $r->cnt,
                ];
            });

        // ISO WEEKS: compute year-week with ISO standard
        $weeksRaw = Detail::select(
            DB::raw("YEARWEEK(first_date, 3) as yw"), // mode 3 => ISO weeks
            DB::raw("MIN(DATE(first_date)) as start_day"),
            DB::raw("MAX(DATE(first_date)) as end_day"),
            DB::raw("COUNT(*) as cnt")
        )
            ->whereNotNull('first_date')
            ->groupBy(DB::raw("YEARWEEK(first_date, 3)"))
            ->orderBy(DB::raw("YEARWEEK(first_date, 3)"), 'desc')
            ->limit(104) // safety cap
            ->get();

        $weeks = $weeksRaw->map(function ($r) {
            // Extract ISO year and week from first_date boundaries
            $start = Carbon::parse($r->start_day)->startOfWeek(Carbon::MONDAY);
            $isoYear = (int) $start->isoFormat('GGGG');
            $isoWeek = (int) $start->isoWeek;
            $end = (clone $start)->endOfWeek(Carbon::SUNDAY);

            return [
                'value' => sprintf('%04d-W%02d', $isoYear, $isoWeek), // e.g., 2025-W42
                'label' => sprintf(
                    'W%02d %s–%s %s',
                    $isoWeek,
                    $start->format('d M'),
                    $end->format('d M'),
                    $isoYear
                ),
                'count' => (int) $r->cnt,
                'range' => [
                    'start' => $start->toDateString(),
                    'end' => $end->toDateString(),
                ],
            ];
        });

        // MONTHS
        $months = Detail::select(
            DB::raw("DATE_FORMAT(first_date, '%Y-%m') as ym"),
            DB::raw("COUNT(*) as cnt")
        )
            ->whereNotNull('first_date')
            ->groupBy(DB::raw("DATE_FORMAT(first_date, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(first_date, '%Y-%m')"), 'desc')
            ->limit(48) // safety cap
            ->get()
            ->map(function ($r) {
                $dt = Carbon::createFromFormat('Y-m', $r->ym)->startOfMonth();
                return [
                    'value' => $dt->format('Y-m'),                 // 2025-10
                    'label' => $dt->format('F Y'),                 // October 2025
                    'count' => (int) $r->cnt,
                    'range' => [
                        'start' => $dt->startOfMonth()->toDateString(),
                        'end' => $dt->endOfMonth()->toDateString(),
                    ],
                ];
            });

        return response()->json([
            'days' => $days,
            'weeks' => $weeks,
            'months' => $months,
        ]);
    }

    public function fetchRecords(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'period' => 'required|in:day,week,month',
            'value' => 'required|string',
            'page' => 'nullable|integer|min:1',
            'perPage' => 'nullable|integer|min:5|max:100',
        ]);

        [$startDate, $endDate] = $this->resolveDateRange($validated['period'], $validated['value']);
        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Invalid period or value'], 422);
        }

        $perPage = (int) ($validated['perPage'] ?? 10);

        $base = Detail::query()
            ->whereBetween(DB::raw('DATE(first_date)'), [$startDate, $endDate]);

        $stats = (clone $base)
            ->selectRaw('
            COUNT(*) as total_records,
            COALESCE(SUM(first_weight),0)  as total_first,
            COALESCE(SUM(second_weight),0) as total_second,
            COALESCE(SUM(net_weight),0)    as total_net,
            COALESCE(SUM(amount),0)        as total_amount
        ')
            ->first();

        $paginator = (clone $base)
            ->select(
                'id',
                'party as partyName',
                'vehicle_number as vehicleNumber',
                'first_weight as firstWeight',
                'first_date as firstDate',
                'second_weight as secondWeight',
                'second_date as secondDate',
                'net_weight as netWeight',
                'description',
                'amount',
                'created_by as committedBy'
            )
            ->orderBy('first_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->appends($validated);

        return response()->json([
            'records' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'stats' => [
                'total_records' => (int) ($stats->total_records ?? 0),
                'total_first' => (float) ($stats->total_first ?? 0),
                'total_second' => (float) ($stats->total_second ?? 0),
                'total_net' => (float) ($stats->total_net ?? 0),
                'total_amount' => (float) ($stats->total_amount ?? 0),
            ],
            'range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ]);
    }

    private function authorizeAdmin()
    {
        if (!Auth::check()) {
            abort(403, 'Admins only');
        }
    }

    private function resolveDateRange(string $period, string $value): array
    {
        try {
            if ($period === 'day') {
                $day = Carbon::createFromFormat('Y-m-d', $value);
                return [$day->toDateString(), $day->toDateString()];
            }

            if ($period === 'week') {
                // Value like 2025-W42
                if (!preg_match('/^(\d{4})-W(\d{2})$/', $value, $m)) {
                    return [null, null];
                }
                $isoYear = (int) $m[1];
                $isoWeek = (int) $m[2];
                $start = Carbon::now()->setISODate($isoYear, $isoWeek)->startOfWeek(Carbon::MONDAY);
                $end = (clone $start)->endOfWeek(Carbon::SUNDAY);
                return [$start->toDateString(), $end->toDateString()];
            }

            if ($period === 'month') {
                // Value like 2025-10
                $month = Carbon::createFromFormat('Y-m', $value)->startOfMonth();
                return [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()];
            }
        } catch (\Throwable $e) {
            return [null, null];
        }
        return [null, null];
    }

    public function showDelete()
    {
        if (!Auth::check() && Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Only admin can delete!');
        }

        return view('pages.delete');
    }

    public function showEdit()
    {
        if (!Auth::check() && Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Only admin can delete!');
        }

        return view('pages.edit');
    }

    public function showrecorduser ($id) 
    {
        $record = Detail::where('id', '=', $id)->first();

        if (!$record) {
            return redirect()->route('print.record')->with('error', 'Please send the serial to that page');
        }

        $user = User::select('phone')->where('username', '=', $record->created_by)->first();

        return view('pages.show', compact('record', 'user'));
    }
}
