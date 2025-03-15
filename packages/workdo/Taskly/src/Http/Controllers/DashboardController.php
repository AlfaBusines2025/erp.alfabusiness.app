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
        if(module_is_active('GoogleAuthentication')) {
            $this->middleware('2fa');
        }
    }

    public function index()
    {
        // 1) Verificar permiso
        if(!Auth::user()->isAbleTo('taskly dashboard manage')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        // 2) Usuario y Workspace
        $userObj          = Auth::user();
        $currentWorkspace = getActiveWorkSpace();  
        
        // Asegurarnos de tener el ID numérico del workspace.
        $workspaceID = is_object($currentWorkspace) 
            ? $currentWorkspace->id 
            : $currentWorkspace;

        // --------------------------------------------
        // 3) LÓGICA PARA CÁLCULO DE DÍAS RESTANTES DE STOCK
        // --------------------------------------------
        $past30Days = Carbon::now()->subDays(30); // Ajusta el rango si quieres
        $today      = Carbon::now();

        // Obtenemos los productos (tipos de comida) del workspace
        $feeds = DB::table('product_services')
            ->where('workspace_id', $workspaceID)
            ->get();

        $daysLeftLabels = [];  // para labels del gráfico
        $daysLeftSeries = [];  // para valores del gráfico
        $feedDaysLeft   = [];  // para detalle en la tabla

        foreach($feeds as $feed)
        {
            // Sumamos cuánta cantidad se ha consumido en los últimos 30 días
            $feedConsumption = DB::table('feed_consumptions')
                ->where('feed_type_id', $feed->id)
                ->where('workspace', $workspaceID)
                ->whereBetween('consumption_date', [$past30Days, $today])
                ->sum('quantity_consumed');

            // Promedio diario de consumo (evitando división por cero)
            $dailyAvg = ($feedConsumption > 0) ? ($feedConsumption / 30) : 0;

            // Días restantes = stock actual / consumo diario
            $daysLeft = ($dailyAvg <= 0)
                ? 0
                : floor($feed->quantity / $dailyAvg);

            // Agregamos a la tabla
            $feedDaysLeft[] = [
                'name'       => $feed->name,
                'sku'        => $feed->sku,
                'quantity'   => $feed->quantity,
                'daily_avg'  => round($dailyAvg, 2),
                'days_left'  => $daysLeft,
            ];

            // Para el gráfico
            $daysLeftLabels[] = $feed->name;
            $daysLeftSeries[] = $daysLeft;
        }

        // 4) Fechas y filtros para Programado vs Consumido
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

        /*
         |--------------------------------------------
         | 1) Alimento PROGRAMADO (feed_schedules)
         |--------------------------------------------
        */
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

        $scheduledData = $scheduledQuery
            ->groupBy('product_services.id', 'product_services.name')
            ->get();

        // Arrays Programado
        $scheduledLabels = [];
        $scheduledSeries = [];
        $scheduledTotal  = $scheduledData->sum('total_scheduled');

        foreach ($scheduledData as $sd) {
            $scheduledLabels[] = $sd->feed_name . ' (' . $sd->total_scheduled . ')';
            $percentage        = ($scheduledTotal > 0)
                ? round(($sd->total_scheduled / $scheduledTotal) * 100, 2)
                : 0;
            $scheduledSeries[] = $percentage;
        }

        /*
         |--------------------------------------------
         | 2) Alimento CONSUMIDO (feed_consumptions)
         |--------------------------------------------
        */
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

        // Arrays Consumido
        $consumedLabels = [];
        $consumedSeries = [];
        $consumedTotal  = $consumptionData->sum('total_consumed');

        foreach ($consumptionData as $cd) {
            $consumedLabels[] = $cd->feed_name . ' (' . $cd->total_consumed . ')';
            $percentage       = ($consumedTotal > 0)
                ? round(($cd->total_consumed / $consumedTotal) * 100, 2)
                : 0;
            $consumedSeries[] = $percentage;
        }

        /*
         |--------------------------------------------
         | 3) Combos (selects) para filtrar
         |--------------------------------------------
        */
        $animalsList = DB::table('animals')
            ->where('species', 'Caballo')
            ->pluck('name', 'id');

        $foodTypesList = DB::table('product_services')
            ->where('workspace_id', $workspaceID)
            ->select('id', 'name')
            ->get();

        /*
         |--------------------------------------------
         | 4) Lógica TASKLY (Proyectos, Bugs, Tasks, etc.)
         |--------------------------------------------
        */
        // Stage 'Completo'
        $doneStage = Stage::where('workspace_id', $workspaceID)
            ->where('created_by', creatorId())
            ->where('complete', '1')
            ->first();

        // Variables por defecto
        $totalProject    = 0;
        $totalBugs       = 0;
        $totalTask       = 0;
        $completeTask    = 0;
        $tasks           = collect();
        $totaltasks      = 0;
        $arrProcessLabel = [];
        $arrProcessPer   = [];
        $arrProcessClass = ['text-success','text-primary','text-danger'];
        $chartData       = ['stages'=>[], 'label'=>[], 'color'=>[]];
        $totalMembers    = 0;

        // ¿Es "client"?
        if(Auth::user()->hasRole('client'))
        {
            // LÓGICA CLIENT
            $totalProject = ClientProject::join("projects","projects.id","=","client_projects.project_id")
                ->where("client_id",$userObj->id)
                ->where('projects.workspace',$workspaceID)
                ->where('projects.type','project')
                ->count();

            $totalBugs = ClientProject::join("bug_reports","bug_reports.project_id","=","client_projects.project_id")
                ->join("projects","projects.id","=","client_projects.project_id")
                ->where('projects.workspace',$workspaceID)
                ->where('projects.type','project')
                ->count();

            $totalTask = ClientProject::join("tasks","tasks.project_id","=","client_projects.project_id")
                ->join("projects","projects.id","=","client_projects.project_id")
                ->where('projects.workspace',$workspaceID)
                ->where('projects.type','project')
                ->where("client_id",$userObj->id)
                ->count();

            if($doneStage){
                $completeTask = ClientProject::join("tasks","tasks.project_id","=","client_projects.project_id")
                    ->join("projects","projects.id","=","client_projects.project_id")
                    ->where('projects.workspace',$workspaceID)
                    ->where('projects.type','project')
                    ->where("client_id",$userObj->id)
                    ->where('tasks.status',$doneStage->id)
                    ->count();
            }

            $tasks = Task::select(['tasks.*','stages.name as status','stages.complete'])
                ->join("client_projects","tasks.project_id","=","client_projects.project_id")
                ->join("projects","projects.id","=","client_projects.project_id")
                ->join("stages","stages.id","=","tasks.status")
                ->where('projects.workspace',$workspaceID)
                ->where("client_id",$userObj->id)
                ->orderBy('tasks.id','desc')
                ->where('projects.type','project')
                ->limit(7)
                ->with('project')
                ->get();

            $totaltasks   = $tasks->count();
            $totalMembers = 0;

            // Proyectos por status
            $projectProcess = ClientProject::join("projects","projects.id","=","client_projects.project_id")
                ->where('projects.workspace',$workspaceID)
                ->where('projects.type','project')
                ->where("client_id",$userObj->id)
                ->groupBy('projects.status')
                ->selectRaw('count(projects.id) as count, projects.status')
                ->pluck('count','projects.status');

            if(count($projectProcess) > 0) {
                foreach($projectProcess as $label => $count) {
                    $arrProcessLabel[] = $label;
                    $arrProcessPer[]   = ($totalProject == 0)
                        ? 0.00
                        : round(($count * 100) / $totalProject, 2);
                }
            } else {
                $arrProcessLabel[] = '';
                $arrProcessPer[]   = 100;
            }

            // chartData
            $chartData = app('Workdo\Taskly\Http\Controllers\ProjectController')->getProjectChart([
                'workspace_id' => $workspaceID,
                'duration'     => 'week',
            ]);
        }
        else
        {
            // LÓGICA NO CLIENT
            $totalProject = UserProject::join("projects","projects.id","=","user_projects.project_id")
                ->where("user_id",$userObj->id)
                ->where('projects.workspace',$workspaceID)
                ->where('projects.type','project')
                ->count();

            $totalBugs = UserProject::join("bug_reports","bug_reports.project_id","=","user_projects.project_id")
                ->join("projects","projects.id","=","user_projects.project_id")
                ->where("user_id",$userObj->id)
                ->where('projects.type','project')
                ->where('projects.workspace',$workspaceID)
                ->count();

            $totalTaskQuery = UserProject::join("tasks","tasks.project_id","=","user_projects.project_id")
                ->join("projects","projects.id","=","user_projects.project_id")
                ->where("user_id",$userObj->id)
                ->where('projects.type','project');

            if(!Auth::user()->hasRole('client') && !Auth::user()->hasRole('company')) {
                if(isset($userObj) && $userObj) {
                    $totalTaskQuery->whereRaw("find_in_set('" . $userObj->id . "',assign_to)");
                }
            }
            $totalTask = $totalTaskQuery->count();

            $tasks = Task::select(['tasks.*','stages.name as status','stages.complete'])
                ->join("user_projects","tasks.project_id","=","user_projects.project_id")
                ->join("projects","projects.id","=","user_projects.project_id")
                ->join("stages","stages.id","=","tasks.status")
                ->where("user_id",$userObj->id)
                ->where('projects.workspace',$workspaceID);

            if (!Auth::user()->hasRole('client') && !Auth::user()->hasRole('company')) {
                if(isset($userObj) && $userObj) {
                    $tasks->whereRaw("find_in_set('" . $userObj->id . "',assign_to)");
                }
            }
            $tasks = $tasks->orderBy('tasks.id','desc')
                ->where('projects.type','project')
                ->limit(7)
                ->with('project')
                ->get();

            $totaltasks = $tasks->count();

            if($doneStage) {
                $completeTask = $tasks->where('status', $doneStage->name)->count();
            }

            // Proceso de proyectos
            $projectProcess = UserProject::join("projects","projects.id","=","user_projects.project_id")
                ->where("user_id",$userObj->id)
                ->where('projects.workspace',$workspaceID)
                ->where('projects.type','project')
                ->groupBy('projects.status')
                ->selectRaw('count(projects.id) as count, projects.status')
                ->pluck('count','projects.status');

            if(count($projectProcess) > 0) {
                foreach($projectProcess as $label => $count) {
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

        // 5) Retornar vista
        return view('taskly::index', compact(
            // Programado vs Consumido
            'scheduledLabels','scheduledSeries',
            'consumedLabels','consumedSeries',
            'animalsList','foodTypesList',
            'feedStartDate','feedEndDate','feedAnimalId','feedTypeId',

            // Variables Taskly
            'totalProject','totalBugs','totalTask','completeTask','tasks','totaltasks',
            'arrProcessLabel','arrProcessPer','arrProcessClass','chartData','totalMembers',
            'currentWorkspace',

            // Variables nuevas del stock de alimento
            'daysLeftLabels',
            'daysLeftSeries',
            'feedDaysLeft'
        ));
    }
}
