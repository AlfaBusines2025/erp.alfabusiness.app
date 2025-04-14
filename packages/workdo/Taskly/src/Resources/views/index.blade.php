@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('page-breadcrumb')
    {{ __('Project') }}
@endsection

@section('content')
    <div class="row row-gap mb-4">
        <div class="col-xl-6 col-12">
            <div class="dashboard-card">
                <img src="{{ asset('assets/images/layer.png')}}" class="dashboard-card-layer" alt="layer">
                <div class="card-inner">
                    <div class="card-content">
                        <h2>{{ Auth::user()->ActiveWorkspaceName() }}</h2>
                        <p>{{ __('Optimizes project management with task tracking, timelines, and real-time progress updates.') }}</p>
                    </div>
                    <div class="card-icon d-flex align-items-center justify-content-center">
                        {{-- Aquí tu SVG o icono --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="row dashboard-wrp">
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="fas fa-tasks text-danger"></i>
                                </div>
                                <a href="{{ route('projects.index') }}">
                                    <h3 class="mt-3 mb-0 text-danger">{{ __('Total Project') }}</h3>
                                </a>
                            </div>
                            <h3 class="mb-0">{{ $totalProject }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="ti ti-file-invoice"></i>
                                </div>
                                <h3 class="mt-3 mb-0">{{ __('Total Task') }}</h3>
                            </div>
                            <h3 class="mb-0">{{ $totalTask }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="fas fa-bug"></i>
                                </div>
                                <h3 class="mt-3 mb-0">{{ __('Total Bug') }}</h3>
                            </div>
                            <h3 class="mb-0">{{ $totalBugs }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="dashboard-project-card">
                        <div class="card-inner d-flex justify-content-between">
                            <div class="card-content">
                                <div class="theme-avtar bg-white">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="mt-3 mb-0">{{ __('Total User') }}</h3>
                            </div>
                            <h3 class="mb-0">{{ $totalMembers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Tasks (siempre) -->
    <div class="row">
        <div class="col-xxl-7 d-flex flex-column">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Tasks') }}</h5>
                        <div class="float-end">
                            <small>
                                <b>{{ $completeTask }}</b> 
                                {{ __('Tasks completed out of') }} 
                                {{ $totaltasks }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-body ">
                    <div class="table-responsive custom-scrollbar" style="max-height: 515px; overflow-y:auto;">
                        <table class="table table-centered table-hover mb-0 animated">
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <div class="font-14 my-1">
                                                <a href="{{ route('projects.task.board', [$task->project_id]) }}" class="text-body">
                                                    {{ $task->title }}
                                                </a>
                                            </div>
                                            @php
                                                $due_date = '<span class="text-'
                                                    . ($task->due_date < date('Y-m-d') ? 'danger' : 'success')
                                                    . '">'
                                                    . date('Y-m-d', strtotime($task->due_date))
                                                    . '</span>';
                                            @endphp
                                            <span class="text-muted font-13">
                                                {{ __('Due Date') }} : {!! $due_date !!}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">{{ __('Status') }}</span> <br />
                                            @if ($task->complete == '1')
                                                <span class="badge bg-success p-2 px-3">{{ __($task->status) }}</span>
                                            @else
                                                <span class="badge bg-primary p-2 px-3">{{ __($task->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">{{ __('Project') }}</span>
                                            <div class="font-14 mt-1 font-weight-normal">
                                                {{ $task->project->name }}
                                            </div>
                                        </td>
                                        @if (Auth::user()->hasRole('client') || Auth::user()->hasRole('client'))
                                            <td>
                                                <span class="text-muted font-13">{{ __('Assigned to') }}</span>
                                                <div class="font-14 mt-1 font-weight-normal">
                                                    @foreach ($task->users() as $user)
                                                        <span class="badge p-2 px-2 bg-secondary">
                                                            {{ $user->name ?? '-' }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    @include('layouts.nodatafound')
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Tasks Overview -->
        <div class="col-xxl-5">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Tasks Overview') }}</h5>
                </div>
                <div class="card-body p-2">
                    <div id="task-area-chart"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i class=""></i></a>
                    </div>
                    <h5>{{ __('Project Status') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-sm-8">
                            <div id="projects-chart"></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-danger"></i>
                                    <span class="ms-2 text-sm">{{ __('On Going') }}</span>
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-warning"></i>
                                    <span class="ms-2 text-sm">{{ __('On Hold') }}</span>
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-primary"></i>
                                    <span class="ms-2 text-sm">{{ __('Finished') }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="row text-center">
                            @foreach ($arrProcessPer as $index => $value)
                                <div class="col-4">
                                    <i class="fas fa-chart"></i>
                                    <h6 class="font-weight-bold"><span>{{ $value }}%</span></h6>
                                    <p class="text-muted">{{ __($arrProcessLabel[$index]) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SOLO si el módulo DairyCattleManagement está activo, mostrar las secciones de alimentación --}}
    @if (module_is_active('DairyCattleManagement'))
        <!-- Sección para el consumo de alimento -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Consumo de Alimento de Caballos</h5>
                    </div>
                    <div class="card-body">
                        <!-- Formulario de filtros para el consumo de alimento -->
                        <form method="GET" action="{{ route('taskly.dashboard') }}" class="mb-4">
                            <div class="row gy-3 align-items-end">
                                <div class="col-sm-3">
                                    <label class="form-label mb-1">Fecha Inicial</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" name="feed_start_date"
                                               value="{{ request('feed_start_date', now()->toDateString()) }}">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label mb-1">Fecha Final</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                        <input type="date" class="form-control" name="feed_end_date"
                                               value="{{ request('feed_end_date', now()->toDateString()) }}">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label mb-1">Caballo</label>
                                    <select name="feed_animal_id" class="form-select">
                                        <option value="">Todos</option>
                                        @foreach($animalsList as $id => $name)
                                            <option value="{{ $id }}" {{ request('feed_animal_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label mb-1">Tipo de Comida</label>
                                    <select name="feed_type_id" class="form-select">
                                        <option value="">Todos</option>
                                        @foreach($foodTypesList as $food)
                                            <option value="{{ $food->id }}" {{ request('feed_type_id') == $food->id ? 'selected' : '' }}>
                                                {{ $food->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-1"></i> Filtrar
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Gráficas Programado vs Consumido -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h6 class="text-center">Alimento Programado</h6>
                                @if(!empty($scheduledSeries) && array_sum($scheduledSeries) > 0)
                                    <div id="scheduled-chart" style="height: 350px;"></div>
                                @else
                                    <p class="text-center mt-2">
                                        No hay registros de alimentación programada en este rango.
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-6 mb-4">
                                <h6 class="text-center">Alimento Consumido</h6>
                                @if(!empty($consumedSeries) && array_sum($consumedSeries) > 0)
                                    <div id="consumption-chart" style="height: 350px;"></div>
                                @else
                                    <p class="text-center mt-2">
                                        No hay registros de alimentación consumida en este rango.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div> <!-- End Card-Body -->
                </div> <!-- End Card -->
            </div> <!-- End Col-12 -->
        </div> <!-- End Row -->

        <!-- NUEVA SECCIÓN: Días de Stock de Alimentos -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Días de Stock de Alimentos</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Este gráfico y tabla muestran cuántos días de inventario quedan para cada tipo de alimento,
                            considerando el consumo de los últimos 30 días como base para el promedio diario.
                        </p>

                        <!-- Gráfico de Barras con ApexCharts -->
                        @if(!empty($daysLeftSeries) && count($daysLeftSeries) > 0)
                            <div id="days-left-chart" style="height: 350px;"></div>
                        @else
                            <p class="text-center mt-2">
                                No hay datos de consumo para calcular los días de stock.
                            </p>
                        @endif

                        <!-- Tabla con el detalle de cada feed -->
                        <div class="table-responsive mt-4">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Comida</th>
                                    <th>SKU</th>
                                    <th>Cantidad en Stock</th>
                                    <th>Consumo Diario (Prom.)</th>
                                    <th>Días Restantes</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($feedDaysLeft as $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['sku'] }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>{{ $item['daily_avg'] }}</td>
                                        <td>
                                            @if($item['days_left'] <= 0)
                                                <span class="badge bg-danger">Agotado o sin consumo</span>
                                            @else
                                                {{ $item['days_left'] }} días
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No hay registros</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- End Card-Body -->
                </div> <!-- End Card -->
            </div> <!-- End Col-12 -->
        </div> <!-- End Row -->
    @endif
@endsection

@push('scripts')
    <!-- Scripts que se usan SIEMPRE (Taskly) -->
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <!-- Chart: Projects Overview -->
    <script>
        (function() {
            var options = {
                chart: {
                    height: 170,
                    type: 'donut'
                },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: { donut: { size: '70%' } }
                },
                series: {!! json_encode($arrProcessPer) !!},
                colors: ['#FF3A6E', '#6fd943', '#ffa21d'],
                labels: {!! json_encode($arrProcessLabel) !!},
                legend: { show: false }
            };
            var chart = new ApexCharts(document.querySelector("#projects-chart"), options);
            chart.render();
        })();
    </script>

    <!-- Chart: Tasks area line chart -->
    <script>
        (function() {
            var options = {
                chart: {
                    height: 157,
                    type: 'line',
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: { width: 2, curve: 'smooth' },
                series: [
                    @foreach ($chartData['stages'] as $id => $name)
                    {
                        name: "{{ __($name) }}",
                        data: {!! json_encode($chartData[$id]) !!}
                    },
                    @endforeach
                ],
                xaxis: {
                    categories: {!! json_encode($chartData['label']) !!}
                },
                colors: {!! json_encode($chartData['color']) !!},
                grid: { strokeDashArray: 4 },
                legend: { show: false },
                yaxis: {
                    tickAmount: 5,
                    min: 1,
                    max: 40
                }
            };
            var chart = new ApexCharts(document.querySelector("#task-area-chart"), options);
            chart.render();
        })();
    </script>

    {{-- Scripts para ALIMENTO solo si el módulo está activo --}}
    @if (module_is_active('DairyCattleManagement'))
        <!-- Donut #1: Programado (Scheduled) -->
        @if(!empty($scheduledSeries) && array_sum($scheduledSeries) > 0)
        <script>
        (function(){
            var scheduledOptions = {
                chart: { height: 350, type: 'donut' },
                dataLabels: { enabled: true },
                plotOptions: {
                    pie: { donut: { size: '70%' } }
                },
                series: {!! json_encode($scheduledSeries) !!},
                labels: {!! json_encode($scheduledLabels) !!},
                colors: ['#FF3A6E', '#6fd943', '#ffa21d', '#18BF6B', '#4E73DF'],
                legend: { position: 'bottom' }
            };
            var scheduledChart = new ApexCharts(document.querySelector("#scheduled-chart"), scheduledOptions);
            scheduledChart.render();
        })();
        </script>
        @endif

        <!-- Donut #2: Consumido (Consumed) -->
        @if(!empty($consumedSeries) && array_sum($consumedSeries) > 0)
        <script>
        (function(){
            var consumedOptions = {
                chart: { height: 350, type: 'donut' },
                dataLabels: { enabled: true },
                plotOptions: {
                    pie: { donut: { size: '70%' } }
                },
                series: {!! json_encode($consumedSeries) !!},
                labels: {!! json_encode($consumedLabels) !!},
                colors: ['#FFC107', '#17A2B8', '#28A745', '#DC3545', '#6610F2'],
                legend: { position: 'bottom' }
            };
            var consumedChart = new ApexCharts(document.querySelector("#consumption-chart"), consumedOptions);
            consumedChart.render();
        })();
        </script>
        @endif

        <!-- Chart de Barras: Días de Stock Restante -->
        @if(!empty($daysLeftSeries) && count($daysLeftSeries) > 0)
        <script>
        (function(){
            var optionsDaysLeft = {
                chart: { type: 'bar', height: 350 },
                series: [{
                    name: 'Días Restantes',
                    data: {!! json_encode($daysLeftSeries) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($daysLeftLabels) !!}
                },
                dataLabels: { enabled: true },
                stroke: { width: 2 },
                legend: { show: false }
            };
            var chartDaysLeft = new ApexCharts(document.querySelector("#days-left-chart"), optionsDaysLeft);
            chartDaysLeft.render();
        })();
        </script>
        @endif
    @endif
@endpush
