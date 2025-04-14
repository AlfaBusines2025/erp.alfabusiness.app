<?php

namespace Workdo\ToDo\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Workdo\ToDo\Entities\ToDo;
use Workdo\ToDo\Entities\TodoStage;
use App\Models\User;
use Illuminate\Support\Str;

class TodoListDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['assigned_to', 'description', 'assigned_by', 'due_date', 'todo_stage_id'];

        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('todo_stage_id', function (ToDo $todo) {
                $todoStages = TodoStage::where(['workspace_id' => getActiveWorkSpace(), 'created_by' => creatorId(), 'name' => 'Completed'])->first();
                $todo->todo_stage_id = $todoStages->id;
                return $todo->todo_stage_id;
            })
            ->editColumn('assigned_to', function (ToDo $todo) {
                $userIds = explode(",", $todo->assigned_to);
                $images = User::whereIn('id', $userIds)->pluck('avatar', 'name')->toArray();

                $html = '';
                foreach ($images as $key => $image) {
                    if (check_file($image) == false) {
                        $path = asset('packages/workdo/ToDo/src/Resources/assets/image/img01.jpg');
                    } else {
                        $path = get_file($image);
                    }

                    $html .= '<img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $key . '" src="' . $path . '" class="rounded-circle" width="25">';
                }
                return $html;
            })
            ->editColumn('description', function ($todo) {
                $url = route('to-do.description', $todo->id);
                $html = '<a class="action-item" data-url="' . $url . '" data-ajax-popup="true" data-bs-toggle="tooltip" title="' . __('Description') . '" data-title="' . __('Description') . '"><i class="fa fa-comment"></i></a>';
                return $html;
            })
            ->editColumn('assigned_by', function (ToDo $todo) {
                return $todo->assignedByUser->name;
            })
            ->editColumn('due_date', function (ToDo $todo) {
                $todo->due_date = company_date_formate($todo->due_date);
                return $todo->due_date;
            });

        if (\Laratrust::hasPermission('todo edit') || \Laratrust::hasPermission('todo delete') || \Laratrust::hasPermission('todo show') || \Laratrust::hasPermission('todo complete')) {

            $dataTable->addColumn('action', function ($todo) {
                return view('to-do::action', compact(['todo']));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ToDo $model, Request $request): QueryBuilder
    {
        $todos = $model->select(
            [
                'todos.*',
                'users.name as user_name',
                'users.avatar',
                'todo_stages.id as todo_stage_id',
            ]
        )->join('users', 'users.id', '=', 'todos.assign_by')
            ->join('todo_stages', 'todo_stages.id', '=', 'todos.status');

        if (!empty($request->stages)) {
            if ($request->stages == 'All') {
                $stages = TodoStage::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])->orderBy('order');

                $todos = ToDo::query()->orderBy('order')->where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()]);
                if ($request->priorities == 'All' || $request->priorities == '') {
                    foreach ($stages as $status) {
                        $todos = $todos->where('status', $status->id);
                    }
                } else {
                    $todos = $todos->where('priority', $request->priorities);
                }
            } else {
                $stages = TodoStage::where(['workspace_id' => getActiveWorkSpace(), 'created_by' => creatorId()])->where('name', $request->stages)->first();

                if ($stages != '') {
                    $todos = ToDo::query()->orderBy('order')->where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()]);

                    if ($request->priorities == 'All' || empty($request->priorities)) {
                        $todos = $todos->where('status', $stages->id);
                    } else {
                        $todos = $todos->where('status', $stages->id)->where('priority', $request->priorities);
                    }
                }
            }

            return $todos;
        } else if (!empty($request->priorities)) {

            $todos = ToDo::query()->orderBy('order')
                ->where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()]);

            if ($request->priorities == 'All') {
                if ($request->stages != 'All') {
                    $stages = TodoStage::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])
                        ->where('name', $request->stages)->first();

                    if ($stages) {
                        $todos = $todos->where('status', $stages->id);
                    }
                }
            } else {
                $todos->where('priority', $request->priorities);

                if ($request->stages != 'All' && !empty($request->stages)) {
                    $stages = TodoStage::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])
                        ->where('name', $request->stages)->first();

                    if ($stages) {
                        $todos = $todos->where('status', $stages->id);
                    }
                }
            }

            return $todos;
        } else
            return $todos;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('todos-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => 'function(d) {
                    var priorities = $("#priorities").val();
                    console.log(priorities);
                    d.priorities = priorities;

                    var stages = $("#stages").val();
                    console.log(stages);
                    d.stages = stages;
                }',
            ])
            ->orderBy(0)
            ->language([
                "paginate" => [
                    "next" => '<i class="ti ti-chevron-right"></i>',
                    "previous" => '<i class="ti ti-chevron-left"></i>'
                ],
                'lengthMenu' => "_MENU_" . __('Entries Per Page'),
                "searchPlaceholder" => __('Search...'),
                "search" => "",
                "info" => __('Showing _START_ to _END_ of _TOTAL_ entries')
            ])
            ->initComplete('function() {
                var table = this;
                $("body").on("change", "#priorities", function() {
                    $("#todos-table").DataTable().draw();
                });
                $("body").on("change", "#stages", function() {
                    $("#todos-table").DataTable().draw();
                });
                var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
                searchInput.removeClass(\'form-control form-control-sm\');
                searchInput.addClass(\'dataTable-input\');
                var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
            }');

        $exportButtonConfig = [
            'extend' => 'collection',
            'className' => 'btn btn-light-secondary dropdown-toggle',
            'text' => '<i class="ti ti-download me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Export"></i>',
            'buttons' => [
                [
                    'extend' => 'print',
                    'text' => '<i class="fas fa-print me-2"></i> ' . __('Print'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="fas fa-file-csv me-2"></i> ' . __('CSV'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="fas fa-file-excel me-2"></i> ' . __('Excel'),
                    'className' => 'btn btn-light text-primary dropdown-item',
                    'exportOptions' => ['columns' => [0, 1, 3]],
                ],
            ],
        ];

        $buttonsConfig = array_merge([
            $exportButtonConfig,
            [
                'extend' => 'reset',
                'className' => 'btn btn-light-danger',
            ],
            [
                'extend' => 'reload',
                'className' => 'btn btn-light-warning',
            ],
        ]);

        $dataTable->parameters([
            "dom" =>  "
        <'dataTable-top'<'dataTable-dropdown page-dropdown'l><'dataTable-botton table-btn dataTable-search tb-search  d-flex justify-content-end gap-2'Bf>>
        <'dataTable-container'<'col-sm-12'tr>>
        <'dataTable-bottom row'<'col-5'i><'col-7'p>>",
            'buttons' => $buttonsConfig,
            "drawCallback" => 'function( settings ) {
                var tooltipTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=tooltip]")
                  );
                  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                  });
                  var popoverTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=popover]")
                  );
                  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                  });
                  var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                  var toastList = toastElList.map(function (toastEl) {
                    return new bootstrap.Toast(toastEl);
                  });
            }'
        ]);

        $dataTable->language([
            'buttons' => [
                'create' => __('Create'),
                'export' => __('Export'),
                'print' => __('Print'),
                'reset' => __('Reset'),
                'reload' => __('Reload'),
                'excel' => __('Excel'),
                'csv' => __('CSV'),
            ]
        ]);

        return $dataTable;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $column = [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('sub_module')->title(__('Module')),
            Column::make('title')->title(__('Name')),
            Column::make('due_date')->title(__('Due Date')),
            Column::make('assigned_by')->title(__('Assigned By'))->searchable(false)->orderable(false),
            Column::make('assigned_to')->title(__('Assigned To'))->exportable(false)->printable(false)->searchable(false)->orderable(false),
            Column::make('description')->title(__('Description')),
        ];
        if (\Laratrust::hasPermission('todo edit') || \Laratrust::hasPermission('todo delete') || \Laratrust::hasPermission('todo show') || \Laratrust::hasPermission('todo complete')) {
            $action = [
                Column::computed('action')->title(__('Action'))
                    ->exportable(false)
                    ->printable(false)
                    ->width(60)
            ];

            $column = array_merge($column, $action);
        }
        return $column;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ToDos_' . date('YmdHis');
    }
}
