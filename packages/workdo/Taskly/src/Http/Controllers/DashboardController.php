<?php

namespace Workdo\Taskly\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Workdo\Taskly\Entities\ClientProject;
use Workdo\Taskly\Entities\Stage;
use Workdo\Taskly\Entities\Task;
use Workdo\Taskly\Entities\UserProject;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (module_is_active('GoogleAuthentication')) {
            $this->middleware('2fa');
        }
    }

	
	public function index()
{
    // 1) Verificar permiso
    if (!Auth::user()->isAbleTo('taskly dashboard manage')) {
        return redirect()->back()->with('error', __('Permission Denied.'));
    }

    // 2) Usuario y Workspace
    $userObj          = Auth::user();
    $currentWorkspace = getActiveWorkSpace();
    $workspaceID      = is_object($currentWorkspace)
        ? $currentWorkspace->id
        : $currentWorkspace;

    // ------------------------------------------------------------------
    // Variables de Taskly
    // ------------------------------------------------------------------
    $totalProject    = 0;
    $totalBugs       = 0;
    $totalTask       = 0;
    $completeTask    = 0;
    $tasks           = collect();
    $totaltasks      = 0;
    $arrProcessLabel = [];
    $arrProcessPer   = [];
    $arrProcessClass = ['text-success', 'text-primary', 'text-danger'];
    $chartData       = ['stages' => [], 'label' => [], 'color' => []];
    $totalMembers    = 0;

    // Stage 'Completo'
    $doneStage = Stage::where('workspace_id', $workspaceID)
        ->where('created_by', creatorId())
        ->where('complete', '1')
        ->first();

    // Lógica de “client” vs “no client”
    if (Auth::user()->hasRole('client')) {
        // LÓGICA CLIENT
        $totalProject = ClientProject::join("projects", "projects.id", "=", "client_projects.project_id")
            ->where("client_id", $userObj->id)
            ->where('projects.workspace', $workspaceID)
            ->where('projects.type', 'project')
            ->count();

        $totalBugs = ClientProject::join("bug_reports", "bug_reports.project_id", "=", "client_projects.project_id")
            ->join("projects", "projects.id", "=", "client_projects.project_id")
            ->where('projects.workspace', $workspaceID)
            ->where('projects.type', 'project')
            ->count();

        $totalTask = ClientProject::join("tasks", "tasks.project_id", "=", "client_projects.project_id")
            ->join("projects", "projects.id", "=", "client_projects.project_id")
            ->where('projects.workspace', $workspaceID)
            ->where('projects.type', 'project')
            ->where("client_id", $userObj->id)
            ->count();

        if ($doneStage) {
            $completeTask = ClientProject::join("tasks", "tasks.project_id", "=", "client_projects.project_id")
                ->join("projects", "projects.id", "=", "client_projects.project_id")
                ->where('projects.workspace', $workspaceID)
                ->where('projects.type', 'project')
                ->where("client_id", $userObj->id)
                ->where('tasks.status', $doneStage->id)
                ->count();
        }

        $tasks = Task::select(['tasks.*', 'stages.name as status', 'stages.complete'])
            ->join("client_projects", "tasks.project_id", "=", "client_projects.project_id")
            ->join("projects", "projects.id", "=", "client_projects.project_id")
            ->join("stages", "stages.id", "=", "tasks.status")
            ->where('projects.workspace', $workspaceID)
            ->where("client_id", $userObj->id)
            ->orderBy('tasks.id', 'desc')
            ->where('projects.type', 'project')
            ->limit(7)
            ->with('project')
            ->get();

        $totaltasks   = $tasks->count();
        $totalMembers = 0;

        // Proyectos por status
        $projectProcess = ClientProject::join("projects", "projects.id", "=", "client_projects.project_id")
            ->where('projects.workspace', $workspaceID)
            ->where('projects.type', 'project')
            ->where("client_id", $userObj->id)
            ->groupBy('projects.status')
            ->selectRaw('count(projects.id) as count, projects.status')
            ->pluck('count', 'projects.status');

        if (count($projectProcess) > 0) {
            foreach ($projectProcess as $label => $count) {
                $arrProcessLabel[] = $label;
                $arrProcessPer[]   = ($totalProject == 0)
                    ? 0.00
                    : round(($count * 100) / $totalProject, 2);
            }
        } else {
            $arrProcessLabel[] = '';
            $arrProcessPer[]   = 100;
        }

        $chartData = app('Workdo\Taskly\Http\Controllers\ProjectController')->getProjectChart([
            'workspace_id' => $workspaceID,
            'duration'     => 'week',
        ]);
    } else {
        // LÓGICA NO CLIENT
        $totalProject = UserProject::join("projects", "projects.id", "=", "user_projects.project_id")
            ->where("user_id", $userObj->id)
            ->where('projects.workspace', $workspaceID)
            ->where('projects.type', 'project')
            ->count();

        $totalBugs = UserProject::join("bug_reports", "bug_reports.project_id", "=", "user_projects.project_id")
            ->join("projects", "projects.id", "=", "user_projects.project_id")
            ->where("user_id", $userObj->id)
            ->where('projects.type', 'project')
            ->where('projects.workspace', $workspaceID)
            ->count();

        $totalTaskQuery = UserProject::join("tasks", "tasks.project_id", "=", "user_projects.project_id")
            ->join("projects", "projects.id", "=", "user_projects.project_id")
            ->where("user_id", $userObj->id)
            ->where('projects.type', 'project');

        if (!Auth::user()->hasRole('client') && !Auth::user()->hasRole('company')) {
            if ($userObj) {
                $totalTaskQuery->whereRaw("find_in_set('" . $userObj->id . "',assign_to)");
            }
        }
        $totalTask = $totalTaskQuery->count();

        $tasks = Task::select(['tasks.*', 'stages.name as status', 'stages.complete'])
            ->join("user_projects", "tasks.project_id", "=", "user_projects.project_id")
            ->join("projects", "projects.id", "=", "user_projects.project_id")
            ->join("stages", "stages.id", "=", "tasks.status")
            ->where("user_id", $userObj->id)
            ->where('projects.workspace', $workspaceID);

        if (!Auth::user()->hasRole('client') && !Auth::user()->hasRole('company')) {
            if ($userObj) {
                $tasks->whereRaw("find_in_set('" . $userObj->id . "',assign_to)");
            }
        }
        $tasks = $tasks->orderBy('tasks.id', 'desc')
            ->where('projects.type', 'project')
            ->limit(7)
            ->with('project')
            ->get();

        $totaltasks = $tasks->count();

        if ($doneStage) {
            $completeTask = $tasks->where('status', $doneStage->name)->count();
        }

        $projectProcess = UserProject::join("projects", "projects.id", "=", "user_projects.project_id")
            ->where("user_id", $userObj->id)
            ->where('projects.workspace', $workspaceID)
            ->where('projects.type', 'project')
            ->groupBy('projects.status')
            ->selectRaw('count(projects.id) as count, projects.status')
            ->pluck('count', 'projects.status');

        if (count($projectProcess) > 0) {
            foreach ($projectProcess as $label => $count) {
                $arrProcessLabel[] = $label;
                $arrProcessPer[]   = ($totalProject == 0)
                    ? 0.00
                    : round(($count * 100) / $totalProject, 2);
            }
        } else {
            $arrProcessLabel[] = '';
            $arrProcessPer[]   = 100;
        }

        $chartData = app('Workdo\Taskly\Http\Controllers\ProjectController')->getProjectChart([
            'workspace_id' => $workspaceID,
            'duration'     => 'week',
        ]);

        $totalMembers = User::where('created_by', creatorId())->emp()->count();
    }

    // ------------------------------------------------------------------
    // Variables de ALIMENTO (solo si DairyCattleManagement ESTÁ ACTIVO)
    // ------------------------------------------------------------------
    // Los gráficos PROGRAMADO y CONSUMIDO usarán los filtros enviados en el request,
    // mientras que la tabla “Días de Stock de Alimentos” se calcula globalmente (últimos 30 días desde ahora).
    $scheduledLabels  = [];
    $scheduledSeries  = [];
    $consumedLabels   = [];
    $consumedSeries   = [];
    $animalsList      = collect();
    $foodTypesList    = collect();
    $feedStartDate    = null;
    $feedEndDate      = null;
    $feedAnimalId     = null;
    $feedTypeId       = null;
    $daysLeftLabels   = [];
    $daysLeftSeries   = [];
    $feedDaysLeft     = [];

    if (module_is_active('DairyCattleManagement')) {

        // (A) Para los gráficos se usan los filtros enviados
        $feedStartDate = request('feed_start_date', Carbon::today()->toDateString());
        $feedEndDate   = request('feed_end_date', Carbon::today()->toDateString());
        $feedAnimalId  = request('feed_animal_id', null);
        $feedTypeId    = request('feed_type_id', null);

        try {
            $feedStartDateTime = Carbon::parse($feedStartDate)->startOfDay();
            $feedEndDateTime   = Carbon::parse($feedEndDate)->endOfDay();
        } catch (\Exception $e) {
            $feedStartDateTime = Carbon::today()->startOfDay();
            $feedEndDateTime   = Carbon::today()->endOfDay();
        }

        // (B) Cálculo global de “Días de Stock de Alimentos” (rango fijo: últimos 30 días desde ahora)
        $globalStartDateTime = Carbon::now()->subDays(30)->startOfDay();
        $globalEndDateTime   = Carbon::now()->endOfDay();
        $totalDaysRange      = 30; // valor fijo

        $feeds = DB::table('product_services')
            ->where('workspace_id', $workspaceID)
            ->get();

        foreach ($feeds as $feed) {
            $schedules = DB::table('feed_schedules')
                ->where('feed_type_id', $feed->id)
                ->where('workspace', $workspaceID)
                ->select('quantity', 'scheduled_time', 'consumption_end')
                ->get();

            $totalUsage = 0;
            $totalDays  = 0;

            foreach ($schedules as $row) {
                $scheduleStart = Carbon::parse($row->scheduled_time);
                // Si consumption_end es nulo se asume que continúa hasta el momento actual
                $scheduleEnd = $row->consumption_end ? Carbon::parse($row->consumption_end) : Carbon::now();

                if ($scheduleEnd->gte($scheduleStart)) {
                    $days = $scheduleEnd->diffInDays($scheduleStart) + 1;
                    $totalUsage += ($row->quantity * $days);
                    $totalDays  += $days;
                }
            }

            $dailyAvg = ($totalDays > 0) ? ($totalUsage / $totalDays) : 0;
            $daysLeft = ($dailyAvg > 0) ? floor($feed->quantity / $dailyAvg) : 0;

            $feedDaysLeft[] = [
                'name'       => $feed->name,
                'sku'        => $feed->sku,
                'quantity'   => $feed->quantity,
                'daily_avg'  => round($dailyAvg, 2),
                'days_left'  => $daysLeft,
            ];

            $daysLeftLabels[] = $feed->name;
            $daysLeftSeries[] = $daysLeft;
        }

        // ------------------------------------------------------------------------------------------------
        // (C) GRÁFICO de Alimento PROGRAMADO (nueva lógica basada en intervalos usando feed_schedules)
        // Se calcula el total programado dentro del rango filtrado considerando solapes:
        $programadoDataArray = [];
        $schedulesProgramado = DB::table('feed_schedules')
            ->join('animals', 'feed_schedules.animal_id', '=', 'animals.id')
            ->join('product_services', 'feed_schedules.feed_type_id', '=', 'product_services.id')
            ->select(
                'product_services.id as product_id',
                'product_services.name as feed_name',
                'feed_schedules.quantity',
                'feed_schedules.scheduled_time',
                'feed_schedules.consumption_end'
            )
            ->where(function ($query) use ($feedStartDateTime, $feedEndDateTime) {
                $query->where(function ($q) use ($feedStartDateTime, $feedEndDateTime) {
                        $q->whereNotNull('consumption_end')
                          ->where('scheduled_time', '<=', $feedEndDateTime)
                          ->where('consumption_end', '>=', $feedStartDateTime);
                    })
                    ->orWhere(function ($q) use ($feedEndDateTime) {
                        $q->whereNull('consumption_end')
                          ->where('scheduled_time', '<=', $feedEndDateTime);
                    });
            })
            ->where('feed_schedules.workspace', $workspaceID)
            ->get();

        foreach ($schedulesProgramado as $row) {
            $scheduleStart = Carbon::parse($row->scheduled_time);
            // Si no hay consumption_end, se asume que continúa hasta la fecha final del filtro
            $scheduleEnd = $row->consumption_end ? Carbon::parse($row->consumption_end) : $feedEndDateTime;
            $overlapStart = $scheduleStart->greaterThan($feedStartDateTime) ? $scheduleStart : $feedStartDateTime;
            $overlapEnd = $scheduleEnd->lessThan($feedEndDateTime) ? $scheduleEnd : $feedEndDateTime;

            if ($overlapEnd->gte($overlapStart)) {
                $days = $overlapEnd->diffInDays($overlapStart) + 1;
                $consumed = $row->quantity * $days;
                if (isset($programadoDataArray[$row->product_id])) {
                    $programadoDataArray[$row->product_id]['total_programado'] += $consumed;
                } else {
                    $programadoDataArray[$row->product_id] = [
                        'feed_name' => $row->feed_name,
                        'total_programado' => $consumed
                    ];
                }
            }
        }

        $programadoTotal = array_sum(array_column($programadoDataArray, 'total_programado'));
        $scheduledLabels = [];
        $scheduledSeries = [];
        foreach ($programadoDataArray as $data) {
            $scheduledLabels[] = $data['feed_name'] . ' (' . $data['total_programado'] . ')';
            $percentage = ($programadoTotal > 0) ? round(($data['total_programado'] / $programadoTotal) * 100, 2) : 0;
            $scheduledSeries[] = $percentage;
        }

        // ------------------------------------------------------------------------------------------------
        // (D) GRÁFICO de Alimento CONSUMIDO (consulta simple a feed_consumptions)
        $consumptionQuery = DB::table('feed_consumptions')
            ->join('animals', 'feed_consumptions.animal_id', '=', 'animals.id')
            ->join('product_services', 'feed_consumptions.feed_type_id', '=', 'product_services.id')
            ->select(
                'product_services.name as feed_name',
                DB::raw('SUM(feed_consumptions.quantity_consumed) as total_consumed')
            )
            ->whereBetween('feed_consumptions.consumption_date', [$feedStartDateTime, $feedEndDateTime])
            ->where('feed_consumptions.workspace', $workspaceID);

        if ($feedAnimalId) {
            $consumptionQuery->where('animals.id', intval($feedAnimalId));
        }
        if ($feedTypeId) {
            $consumptionQuery->where('product_services.id', intval($feedTypeId));
        }
        $consumptionData = $consumptionQuery
            ->groupBy('product_services.id', 'product_services.name')
            ->get();

        $consumedTotal = $consumptionData->sum('total_consumed');
        $consumedLabels = [];
        $consumedSeries = [];
        foreach ($consumptionData as $cd) {
            $consumedLabels[] = $cd->feed_name . ' (' . $cd->total_consumed . ')';
            $percentage = ($consumedTotal > 0) ? round(($cd->total_consumed / $consumedTotal) * 100, 2) : 0;
            $consumedSeries[] = $percentage;
        }

        // (E) Combos de animales y tipos de comida
        $animalsList = DB::table('animals')
            ->where('species', 'Caballo')
            ->pluck('name', 'id');

        $foodTypesList = DB::table('product_services')
            ->where('workspace_id', $workspaceID)
            ->select('id', 'name')
            ->get();

        return view('taskly::index', compact(
            // Taskly:
            'currentWorkspace',
            'totalProject',
            'totalBugs',
            'totalTask',
            'totalMembers',
            'arrProcessLabel',
            'arrProcessPer',
            'arrProcessClass',
            'completeTask',
            'tasks',
            'chartData',
            'totaltasks',
            // DairyCattleManagement:
            'scheduledLabels',
            'scheduledSeries',
            'consumedLabels',
            'consumedSeries',
            'animalsList',
            'foodTypesList',
            'feedStartDate',
            'feedEndDate',
            'feedAnimalId',
            'feedTypeId',
            'daysLeftLabels',
            'daysLeftSeries',
            'feedDaysLeft'
        ));
    } else {
        return view('taskly::index', compact(
            'currentWorkspace',
            'totalProject',
            'totalBugs',
            'totalTask',
            'totalMembers',
            'arrProcessLabel',
            'arrProcessPer',
            'arrProcessClass',
            'completeTask',
            'tasks',
            'chartData',
            'totaltasks'
        ));
    }
}


	
	
    //////////////////////////////////////////
}
