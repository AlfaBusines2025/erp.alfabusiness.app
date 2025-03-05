<?php

namespace Workdo\DairyCattleManagement\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\Entities\DailyMilkSheet;
use Workdo\DairyCattleManagement\Entities\MilkInventory;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DailyMilkSheetDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['id', 'animal_id', 'morning_milk', 'evening_milk'];
        $dataTable =  (new EloquentDataTable($query))
            ->filterColumn('id', function ($query, $keyword) {
                $formattedValue = str_replace('DMS0000', '', $keyword);
                $query->where('daily_milk_sheets.id', $formattedValue);
            })
            ->editColumn('id', function (DailyMilkSheet $model) {
                $url = route('dailymilksheet.show', \Crypt::encrypt($model->id));
                $html = '<a href="'.$url.'" class="btn btn-outline-primary">DMS0000' . $model->id . '</a>';
                return $html;
            })
            ->editColumn('animal_id', function (DailyMilkSheet $model) {
                return $model->animal_name;
            })
            ->editColumn('morning_milk', function (DailyMilkSheet $model) {
                return $model->getAmTotal();
            })
            ->editColumn('evening_milk', function (DailyMilkSheet $model) {
                return $model->getPmTotal();
            });

        $dataTable->addColumn('total_milk', function (DailyMilkSheet $dailymilksheet) {
            return view('dairy-cattle-management::dailymilksheet.total', compact('dailymilksheet'));
        });
        if (\Laratrust::hasPermission('dailymilksheet show') || \Laratrust::hasPermission('dailymilksheet edit') || \Laratrust::hasPermission('dailymilksheet delete')) {
            $dataTable->addColumn('action', function (DailyMilkSheet $dailymilksheet) {
                return view('dairy-cattle-management::dailymilksheet.action', compact('dailymilksheet'));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(DailyMilkSheet $model): QueryBuilder
    {
        $dailymilksheets = $model
            ->join('animals', 'daily_milk_sheets.animal_id', '=', 'animals.id')
            ->select('daily_milk_sheets.*', 'animals.name as animal_name')
            ->where('daily_milk_sheets.workspace', getActiveWorkSpace())
            ->where('daily_milk_sheets.created_by', creatorId());
        return $dailymilksheets;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('DailyMilkSheet-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            Column::make('id')->title(__('#'))->orderable(false),
            Column::make('animal_id')->title(__('Animal Name'))->name('animals.name'),
            Column::make('start_date')->title(__('Start Date')),
            Column::make('end_date')->title(__('End Date')),
            Column::make('morning_milk')->title(__('Morning Milk')),
            Column::make('evening_milk')->title(__('Evening Milk')),
            Column::computed('total_milk')->title(__('Total Milk'))

        ];

        if (
            \Laratrust::hasPermission('dailymilksheet edit') ||
            \Laratrust::hasPermission('dailymilksheet delete') ||
            \Laratrust::hasPermission('dailymilksheet show')
        ) {
            $action = [
                Column::computed('action')
                ->title(__('Action'))
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
        return 'DailyMilkSheet_' . date('YmdHis');
    }
}
