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
        // Variables que SIEMPRE se retornan (Taskly, etc.)
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

            // 1) Stock de alimento (días restantes)
            $past30Days = Carbon::now()->subDays(30);
            $today      = Carbon::now();

            $feeds = DB::table('product_services')
                ->where('workspace_id', $workspaceID)
                ->get();

            foreach ($feeds as $feed) {
                $schedules = DB::table('feed_schedules')
                    ->where('feed_type_id', $feed->id)
                    ->where('workspace', $workspaceID)
                    ->whereBetween('scheduled_time', [$past30Days, $today])
                    ->select('quantity', 'scheduled_time')
                    ->get();

                $sumUsage = 0;
                foreach ($schedules as $row) {
                    $daysPassed = Carbon::parse($row->scheduled_time)->diffInDays($today);
                    if ($daysPassed < 1) {
                        $daysPassed = 1;
                    }
                    $sumUsage += ($row->quantity * $daysPassed);
                }

                $dailyAvg = ($sumUsage > 0)
                    ? ($sumUsage / 30)
                    : 0;

                $daysLeft = ($dailyAvg <= 0)
                    ? 0
                    : floor($feed->quantity / $dailyAvg);

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

            // 2) Programado vs Consumido
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

            // Alimento PROGRAMADO
            $scheduledQuery = DB::table('feed_schedules')
                ->join('animals', 'feed_schedules.animal_id', '=', 'animals.id')
                ->join('product_services', 'feed_schedules.feed_type_id', '=', 'product_services.id')
                ->select(
                    'product_services.name as feed_name',
                    DB::raw('SUM(feed_schedules.quantity) as total_scheduled')
                )
                ->whereBetween('feed_schedules.scheduled_time', [$feedStartDateTime, $feedEndDateTime])
                ->where('feed_schedules.workspace', $workspaceID);

            if ($feedAnimalId) {
                $scheduledQuery->where('animals.id', intval($feedAnimalId));
            }
            if ($feedTypeId) {
                $scheduledQuery->where('product_services.id', intval($feedTypeId));
            }
            $scheduledData  = $scheduledQuery
                ->groupBy('product_services.id', 'product_services.name')
                ->get();

            $scheduledTotal = $scheduledData->sum('total_scheduled');

            foreach ($scheduledData as $sd) {
                $scheduledLabels[] = $sd->feed_name . ' (' . $sd->total_scheduled . ')';
                $percentage        = ($scheduledTotal > 0)
                    ? round(($sd->total_scheduled / $scheduledTotal) * 100, 2)
                    : 0;
                $scheduledSeries[] = $percentage;
            }

            // Alimento CONSUMIDO
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

            foreach ($consumptionData as $cd) {
                $consumedLabels[] = $cd->feed_name . ' (' . $cd->total_consumed . ')';
                $percentage       = ($consumedTotal > 0)
                    ? round(($cd->total_consumed / $consumedTotal) * 100, 2)
                    : 0;
                $consumedSeries[] = $percentage;
            }

            // Combos de animales y tipos de comida
            $animalsList = DB::table('animals')
                ->where('species', 'Caballo')
                ->pluck('name', 'id');

            $foodTypesList = DB::table('product_services')
                ->where('workspace_id', $workspaceID)
                ->select('id', 'name')
                ->get();
        }

        // ------------------------------------------------------------------
        // Al final, retornamos la vista con lo siguiente:
        // 1) SI el módulo DairyCattleManagement está activo, mandamos todo.
        // 2) SI NO está activo, mandamos solo las variables de Taskly.
        // ------------------------------------------------------------------
        if (module_is_active('DairyCattleManagement')) {
            // Retornar TODAS las variables
            return view('taskly::index', compact(
                // SIEMPRE:
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

                // SOLO si DCM activo
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
            // Retornar SOLO las variables "globales" de Taskly
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
}
