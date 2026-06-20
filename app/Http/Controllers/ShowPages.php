<?php

namespace App\Http\Controllers;

use App\Models\Detail;
use App\Models\Metadata;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;

class ShowPages extends Controller
{
    public function showdashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Login to access this page please');
        }

        $today_weight = Detail::where('created_by', Auth::user()->username)->whereBetween('created_at', [
            Carbon::now()->startOfDay(),
            Carbon::now()->endOfDay(),
        ])->count();
        $week_weight = Detail::where('created_by', Auth::user()->username)->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),   // Monday by default
            Carbon::now()->endOfWeek()
        ])->count();
        $month_weight = Detail::where('created_by', Auth::user()->username)->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])->count();

        $amount_month = Detail::where('created_by', Auth::user()->username)
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])
            ->sum('amount');

        $recent_weights = Detail::orderBy('created_at', 'desc')->take(5)->get();
        return view('pages.dashboard', [
            'today_weight' => $today_weight,
            'weekly_weight' => $week_weight,
            'monthly_weight' => $month_weight,
            'amount_month' => $amount_month,
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

        $layoutKey = request()->query('layout', 'layout1');

        // Map layout key to blade file names
        $layoutMap = [
            'layout1' => 'partials.print-layout1',
            'layout2' => 'partials.print-layout2',
            'layout3' => 'partials.print-layout3',
            'layout4' => 'partials.print-layout4',
            'layout5' => 'partials.print-layout5',
            'layout6' => 'partials.print-layout6',
            'layout7' => 'partials.print-layout7',
            'layout8' => 'partials.print-layout8',
            'layout9' => 'partials.print-layout9',
            'layout10' => 'partials.print-layout10',
            'layout11' => 'partials.print-layout11',
            'layout12' => 'partials.print-layout12',
            'layout13' => 'partials.print-layout13',
            'layout14' => 'partials.print-layout14',
        ];

        $viewName = $layoutMap[$layoutKey] ?? 'partials.print-layout1';

        $url = "https://kanta.malick.site/show/$record->id";
        $qrCode = QrCode::size(60)->generate($url);

        $user = User::where('username', '=', $record->created_by)->first();

        $isPreview = false;

        $address = Metadata::where('name', 'Address')->first();

        if(!$address) {
            return redirect()->back()->with('error', 'Address not found!');
        }

        $company = Metadata::where('name', 'Company_Name')->first();

        if(!$company) {
            return redirect()->back()->with('error', 'Company Name not found!');
        }

        $contact = Metadata::where('name', '=', 'Contact_Number')->first();

        return view($viewName, compact('record', 'user', 'qrCode', 'isPreview', 'address', 'company', 'contact'));
    }

    public function showprintpreview()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Not allowed to visit');
        }

        // Generate dummy data for the preview
        $record = (object) [
            'id' => 12345,
            'party' => 'Demo Customer LLC',
            'vehicle_number' => 'ABC-1234',
            'first_date' => Carbon::now()->subHours(2),
            'second_date' => Carbon::now(),
            'first_weight' => 15000,
            'second_weight' => 5000,
            'net_weight' => 10000,
            'description' => 'Rice Husk',
            'amount' => 500,
            'created_by' => 'demo_user'
        ];

        $user = (object) [
            'name' => 'John Doe',
            'phone' => '0300-1234567'
        ];

        $url = "https://kanta.malick.site/demo/12345";
        $qrCode = QrCode::size(60)->generate($url);

        $layoutKey = request()->query('layout', 'layout1');

        $layoutMap = [
            'layout1' => 'partials.print-layout1',
            'layout2' => 'partials.print-layout2',
            'layout3' => 'partials.print-layout3',
            'layout4' => 'partials.print-layout4',
            'layout5' => 'partials.print-layout5',
            'layout6' => 'partials.print-layout6',
            'layout7' => 'partials.print-layout7',
            'layout8' => 'partials.print-layout8',
            'layout9' => 'partials.print-layout9',
            'layout10' => 'partials.print-layout10',
            'layout11' => 'partials.print-layout11',
            'layout12' => 'partials.print-layout12',
            'layout13' => 'partials.print-layout13',
            'layout14' => 'partials.print-layout14',
        ];

        $viewName = $layoutMap[$layoutKey] ?? 'partials.print-layout1';

        $isPreview = true;

        return view($viewName, compact('record', 'user', 'qrCode', 'isPreview'));
    }

    public function showSettings()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Please login to access settings');
        }

        $address = Metadata::where('name', '=', 'address')->firstOrFail();
        $company = Metadata::where('name', '=', 'Company_Name')->firstOrFail();
        $contact = Metadata::where('name', '=', 'Contact_Number')->first();

        return view('pages.settings', compact('address', 'company', 'contact'));
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

    public function reportPage()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Login to access this page');
        }

        return view('pages.report');
    }

    public function fetchReport(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'filter_type' => 'required|in:date,serial',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'from_serial' => 'nullable|integer',
            'to_serial' => 'nullable|integer',
        ]);

        $query = Detail::query();

        if ($validated['filter_type'] === 'date') {
            if (!empty($validated['from_date'])) {
                $to_date = !empty($validated['to_date']) ? $validated['to_date'] : \Carbon\Carbon::today()->toDateString();
                $query->whereBetween(DB::raw('DATE(first_date)'), [$validated['from_date'], $to_date]);
            }
        } else {
            if (!empty($validated['from_serial'])) {
                if (!empty($validated['to_serial'])) {
                    $query->whereBetween('id', [$validated['from_serial'], $validated['to_serial']]);
                } else {
                    $query->where('id', '>=', $validated['from_serial']);
                }
            }
        }

        $records = $query->select(
            'id',
            'party',
            'first_weight',
            'second_weight',
            'net_weight',
            'amount',
            'first_date',
            'second_date'
        )->orderBy('id', 'asc')->get();

        return response()->json([
            'records' => $records
        ]);
    }

    public function printReport(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Login to access this page');
        }

        $filter_type = $request->query('filter_type');
        $query = Detail::query();

        if ($filter_type === 'date') {
            $from_date = $request->query('from_date');
            $to_date = $request->query('to_date') ?: \Carbon\Carbon::today()->toDateString();
            if ($from_date) {
                $query->whereBetween(DB::raw('DATE(first_date)'), [$from_date, $to_date]);
            }
        } elseif ($filter_type === 'serial') {
            $from_serial = $request->query('from_serial');
            $to_serial = $request->query('to_serial');
            if ($from_serial) {
                if ($to_serial) {
                    $query->whereBetween('id', [$from_serial, $to_serial]);
                } else {
                    $query->where('id', '>=', $from_serial);
                }
            }
        }

        $records = $query->select(
            'id',
            'party',
            'first_weight',
            'second_weight',
            'net_weight',
            'amount',
            'first_date',
            'second_date'
        )->orderBy('id', 'asc')->get();

        return view('partials.report-print', compact('records', 'filter_type'));
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

    public function showrecorduser($id)
    {
        $record = Detail::where('id', '=', $id)->first();

        if (!$record) {
            return redirect()->route('print.record')->with('error', 'Please send the serial to that page');
        }

        $user = User::select('phone')->where('username', '=', $record->created_by)->first();

        return view('pages.show', compact('record', 'user'));
    }

    public function showprofile()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Please login to access this page');
        }

        return view('pages.profile');
    }

    public function showallusers()
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('warning', 'Login to access user management page');
        }
        if (Auth::user()->role !== 'admin') {
            return redirect()->back(403)->with('error', 'Only admins are allowed to visit the page');
        }

        return view('pages.user-management');
    }

    public function showDeletedRecords()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $records = Detail::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        return view('pages.deleted-records', compact('records'));
    }

    public function addressupdate(Request $request)
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You are not logged in or not admin!',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'address' => 'required|min:20|max:50|string',
        ], [
            'address.required' => 'Address bar should be filled!',
            'address.min' => 'Address should be atleast 20 characters long',
            'address.max' => 'Address should not be longer than 50 characters',
            'address.string' => 'Address should be string (TEXT)',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $record = Metadata::where('name', 'address')->firstOrFail();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'No address was found to be updated',
            ]);
        }

        $user = Auth::user();

        $record->value = $request->input('address');

        if ($record->save()) {
            Log::info("Address was updated to $record->value by $user->username");

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to save Address',
            ]);
        }
    }

    public function companynameupdate(Request $request)
    {
        if(!Auth::check() || Auth::user()->role != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You are not logged in or you do not have permission to update!',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'companyname' => 'required|string|max:40|min:20',
        ], [
            'companyname.required' => 'Name is required!',
            'companyname.string' => 'It is not a valid string',
            'companyname.max' => 'Max 40 characters are allowed',
            'companyname.min' => 'Minimum 20 characters are required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $record = Metadata::where('name', 'Company_Name')->first();

        if(!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Company Name was not found!',
            ]);
        }

        $record->value = $request->input('companyname');

        $user = Auth::user();

        if($record->save()) {
            Log::info("Company Name was updated to $record->value by $user->username");
            return response()->json([
                'success' => true,
                'message' => 'Company Name was updated successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Unable to update the company name!",
            ]);
        }

    }

    public function contactnumupdate(Request $request)
    {
        if(!Auth::check() || Auth::user()->role != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to perform this operation!'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'contact' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $record = Metadata::where('name', '=', 'Contact_Number')->first();

        if(!$record) {
            Metadata::create([
                'name' => 'Contact_Number',
                'value' => $request->input('contact'),
            ]);

            return response()-> json([
                'success' => true,
                'message' => 'No record was found so created a new record!',
            ]);
        } else {
            $record->value = $request->input('contact');

            if($record->save()){
                return response()->json([
                    'success' => true,
                    'message' => "Contact Number Updated Successfully",
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to save Contact number!',
                ]);
            }
        }
    }
}
