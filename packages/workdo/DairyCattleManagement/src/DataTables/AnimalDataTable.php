<?php

namespace Workdo\DairyCattleManagement\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Workdo\DairyCattleManagement\Entities\Animal;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AnimalDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $rowColumn = ['image','health_status', 'breeding'];
        $dataTable = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('image', function (Animal $model) {
                if (check_file($model->image) == false) {
                    $path = asset('packages/workdo/DairyCattleManagement/src/Resources/assets/image/img01.jpg');
                } else {
                    $path = get_file($model->image);
                }
                $html = '<div class="image-fixsize"><a href="' . $path . '" target="_blank">
                            <img src="' . $path . '" class="border border-primary border-2 rounded me-3 card-avatar" width="50" id="blah3">
                        </a></div>';
                return $html;
            })
            ->filterColumn('health_status', function ($query, $keyword) {
                if (stripos('Healthy', $keyword) !== false) {
                    $query->where('health_status', 0);
                }
                elseif (stripos('Sick', $keyword) !== false) {
                    $query->orWhere('health_status', 1);
                }
                elseif (stripos('Injured', $keyword) !== false) {
                    $query->orWhere('health_status', 2);
                }
            })
            ->addColumn('health_status', function (Animal $model) {
                $statuses = [
                    0 => 'bg-primary',
                    1 => 'bg-info',
                    2 => 'bg-danger',
                ];

                $class = isset($statuses[$model->health_status]) ? $statuses[$model->health_status] : '';
                return '<span class="purchase_status badge ' . $class . ' status-btn p-2 px-3">' . Animal::$healthstatus[$model->health_status] . '</span>';
            })
            ->filterColumn('breeding', function ($query, $keyword) {
                if (stripos('Ready For Breeding', $keyword) !== false) {
                    $query->where('breeding', 0);
                }
                elseif (stripos('Pregnant', $keyword) !== false) {
                    $query->orWhere('breeding', 1);
                }
                elseif (stripos('Not Ready', $keyword) !== false) {
                    $query->orWhere('breeding', 2);
                }
            })
            ->addColumn('breeding', function (Animal $model) {
                $statuses = [
                    0 => 'bg-primary',
                    1 => 'bg-info',
                    2 => 'bg-danger',
                ];

                $class = isset($statuses[$model->breeding]) ? $statuses[$model->breeding] : '';
                return '<span class="purchase_status badge ' . $class . ' status-btn p-2 px-3">' . Animal::$breedingstatus[$model->breeding] . '</span>';
            });

        if (\Laratrust::hasPermission('animal show') || \Laratrust::hasPermission('animal edit') || \Laratrust::hasPermission('animal delete')) {
            $dataTable->addColumn('action', function (Animal $animal) {
                return view('dairy-cattle-management::animal.action', compact('animal'));
            });
            $rowColumn[] = 'action';
        }

        return $dataTable->rawColumns($rowColumn);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Animal $model): QueryBuilder
    {
        $animals = $model->where('workspace', getActiveWorkSpace())->where('created_by', creatorId());
        return $animals;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $dataTable = $this->builder()
            ->setTableId('animal-table')
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
            Column::make('image')->title(__('Image'))->orderable(false),
            Column::make('name')->title(__('Name')),
            Column::make('species')->title(__('Species')),
            Column::make('breed')->title(__('Breed')),
            Column::make('birth_date')->title(__('Date Of Birth')),
            Column::make('gender')->title(__('Gender')),
            Column::make('health_status')->title(__('Health Status')),
            Column::make('weight')->title(__('Weight')),
            Column::make('breeding')->title(__('Breeding Status')),
        ];

        if (\Laratrust::hasPermission('animal show') || \Laratrust::hasPermission('animal edit') || \Laratrust::hasPermission('animal delete')) {
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
        return 'Animals_' . date('YmdHis');
    }
}
