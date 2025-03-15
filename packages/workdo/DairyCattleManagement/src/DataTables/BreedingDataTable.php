<?php

namespace Workdo\DairyCattleManagement\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\Entities\Breeding;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BreedingDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['animal_id', 'breeding_status'];
        $dataTable =  (new EloquentDataTable($query))
            ->addIndexColumn()
            ->filterColumn('breeding_status', function ($query, $keyword) {
                if (stripos('Pregnant', $keyword) !== false) {
                    $query->where('breeding_status', 0);
                } elseif (stripos('Not Pregnant', $keyword) !== false) {
                    $query->orWhere('breeding_status', 1);
                } elseif (stripos('Heat', $keyword) !== false) {
                    $query->orWhere('breeding_status', 2);
                }
            })
            ->addColumn('breeding_status', function (Breeding $model) {
                $statuses = [
                    0 => 'bg-primary',
                    1 => 'bg-info',
                    2 => 'bg-danger'
                ];

                $class = isset($statuses[$model->breeding_status]) ? $statuses[$model->breeding_status] : '';
                return '<span class="purchase_status badge ' . $class . ' status-btn p-2 px-3">' . Breeding::$breedingstatus[$model->breeding_status] . '</span>';
            })
            ->editColumn('animal_id', function (Breeding $model) {
                return $model->animal_name;
            });

        if (\Laratrust::hasPermission('breeding show') || \Laratrust::hasPermission('breeding edit') || \Laratrust::hasPermission('breeding delete')) {
            $dataTable->addColumn('action', function (Breeding $breeding) {
                return view('dairy-cattle-management::breeding.action', compact('breeding'));
            });
            $rowColumn[] = 'action';
        }
        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Breeding $model): QueryBuilder
    {
        $breedings = $model
            ->join('animals', 'breedings.animal_id', '=', 'animals.id')
            ->select('breedings.*', 'animals.name as animal_name')
            ->where('breedings.workspace', getActiveWorkSpace())
            ->where('breedings.created_by', creatorId());
		
		// Filtrar si existe el parámetro 'animal_id' en la URL 
		if (request()->has('animal_id')) { 
			$breedings->where('breedings.animal_id', request()->query('animal_id')); 
		}
		
        return $breedings;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('Breedings-table')
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
        $column =  [
            Column::make('id')->searchable(false)->visible(false)->exportable(false)->printable(false),
            Column::make('No')->title(__('No'))->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('animal_id')->title(__('Animal Name'))->name('animals.name'),
            Column::make('breeding_date')->title(__('Breeding Date')),
            Column::make('gestation')->title(__('Gestation Period')),
            Column::make('due_date')->title(__('Expected Due Date')),
            Column::make('breeding_status')->title(__('Breeding Status')),
            Column::make('note')->title(__('Breeding Notes'))
        ];

        if (\Laratrust::hasPermission('breeding show') || \Laratrust::hasPermission('breeding edit') || \Laratrust::hasPermission('breeding delete')) {
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
        return 'Breedings_' . date('YmdHis');
    }
}
