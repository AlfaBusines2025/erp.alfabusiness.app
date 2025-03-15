@extends('layouts.main')
@section('page-title')
    {{ __('Animal Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Animal Detail') }}
@endsection

@section('page-action')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <!-- Primera fila: Información básica e imagen -->
                    <div class="row">
                        <!-- Columna 1: Datos básicos -->
                        <div class="col-md-4">
                            <p><b>{{ __('Name') }}</b>: {!! !empty($animal->name) ? $animal->name : '--' !!}</p>
                            <p><b>{{ __('Species') }}</b>: {!! !empty($animal->species) ? $animal->species : '--' !!}</p>
                            <p><b>{{ __('Breed') }}</b>: {!! !empty($animal->breed) ? $animal->breed : '--' !!}</p>
                            <p><b>{{ __('Date Of Birth') }}</b>: {!! !empty($animal->birth_date) ? $animal->birth_date : '--' !!}</p>
                            <p><b>{{ __('Gender') }}</b>: {!! !empty($animal->gender) ? $animal->gender : '--' !!}</p>
                            <p><b>{{ __('Owner Name') }}</b>: {!! !empty($animal->nombre_propietario_animal) ? $animal->nombre_propietario_animal : '--' !!}</p>
                            <p><b>{{ __('Diet Notes') }}</b>: {!! !empty($animal->notas_dieta_animal) ? $animal->notas_dieta_animal : '--' !!}</p>
                        </div>
                        <!-- Columna 2: Salud, notas y nuevos campos en 2 columnas -->
                        <div class="col-md-4">
                            <p>
                                <b>{{ __('Health Status') }}</b>:
                                @if ($animal->health_status == 0)
                                    <span class="p-2 px-3 badge fix_badges bg-primary bill_status">
                                        {{ __(Workdo\DairyCattleManagement\Entities\Animal::$healthstatus[$animal->health_status]) }}
                                    </span>
                                @elseif($animal->health_status == 1)
                                    <span class="p-2 px-3 badge fix_badges bg-info bill_status">
                                        {{ __(Workdo\DairyCattleManagement\Entities\Animal::$healthstatus[$animal->health_status]) }}
                                    </span>
                                @elseif($animal->health_status == 2)
                                    <span class="p-2 px-3 badge fix_badges bg-danger bill_status">
                                        {{ __(Workdo\DairyCattleManagement\Entities\Animal::$healthstatus[$animal->health_status]) }}
                                    </span>
                                @endif
                            </p>
                            <p><b>{{ __('Weight') }}</b>: {!! !empty($animal->weight) ? $animal->weight : '--' !!}</p>
                            <p>
                                <b>{{ __('Breeding Status') }}</b>:
                                @if ($animal->breeding == 0)
                                    <span class="p-2 px-3 badge fix_badges bg-primary bill_status">
                                        {{ __(Workdo\DairyCattleManagement\Entities\Animal::$breedingstatus[$animal->breeding]) }}
                                    </span>
                                @elseif($animal->breeding == 1)
                                    <span class="p-2 px-3 badge fix_badges bg-info bill_status">
                                        {{ __(Workdo\DairyCattleManagement\Entities\Animal::$breedingstatus[$animal->breeding]) }}
                                    </span>
                                @elseif($animal->breeding == 2)
                                    <span class="p-2 px-3 badge fix_badges bg-danger bill_status">
                                        {{ __(Workdo\DairyCattleManagement\Entities\Animal::$breedingstatus[$animal->breeding]) }}
                                    </span>
                                @endif
                            </p>
                            <p><b>{{ __('Notes/Comments') }}</b>: {!! !empty($animal->note) ? $animal->note : '--' !!}</p>

                            <!-- Nuevos campos organizados en 2 columnas -->
                            <div class="row">
                                <div class="col-6">
                                    <p><b>{{ __('Stable Number') }}</b>: {!! !empty($animal->numero_pesebrera_animal) ? $animal->numero_pesebrera_animal : '--' !!}</p>
                                    <p><b>{{ __('Stable Facilities') }}</b>: {!! !empty($animal->instalaciones_pesebrera_animal) ? $animal->instalaciones_pesebrera_animal : 'PORTATILES' !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Columna 3: Imagen -->
                        <div class="col-md-4 text-end">
                            <a href="{{ !empty($animal->image) ? get_file($animal->image) : asset('packages/workdo/DairyCattleManagement/src/Resources/assets/image/img01.jpg') }}"
                                target="_blank">
                                <img src="{{ !empty($animal->image) ? get_file($animal->image) : asset('packages/workdo/DairyCattleManagement/src/Resources/assets/image/img01.jpg') }}"
                                    class="rounded me-3" style="width:256px;">
                            </a>
                        </div>
						
                    </div>

                    <!-- Acciones y vitas animal -->
                    <div class="mt-2 row items-align-center text-center">
						<div class="col-md-8">
								<a class="btn btn-sm btn-warning btn-icon m-2 text-white" data-bs-toggle="tooltip"
									data-bs-placement="top" title="" data-ajax-popup="true" data-size="lg"
									data-title="Editar animales"
									data-url="https://erp.alfabusiness.app/animal/{!! $animal->id !!}/edit"
									data-bs-original-title="Editar" aria-label="Editar">
									<i class="text-white ti ti-pencil"></i> Editar a {!! !empty($animal->name) ? $animal->name : '--' !!}
								</a>
								<a class="btn btn-sm btn-primary btn-icon m-2 text-white" data-bs-toggle="tooltip" data-bs-placement="top"
									title="" 
									href="https://erp.alfabusiness.app/health/?animal_id={!! $animal->id !!}"
									data-bs-original-title="Ver" aria-label="Crear">
									<i class="text-white ti ti-eye"></i> Ver Salud
								</a>
								<a class="btn btn-sm btn-primary btn-icon m-2 text-white" data-bs-toggle="tooltip" data-bs-placement="top"
									title="" href="https://erp.alfabusiness.app/breeding/?animal_id={!! $animal->id !!}"
									data-bs-original-title="Ver" aria-label="Crear">
									<i class="text-white ti ti-eye"></i> Ver Crías
								</a>
								<a class="btn btn-sm btn-primary btn-icon m-2 text-white" data-bs-toggle="tooltip" data-bs-placement="top"
									title="" href="https://erp.alfabusiness.app/weight/?animal_id={!! $animal->id !!}"
									data-bs-original-title="Ver" aria-label="Crear">
									<i class="text-white ti ti-eye"></i> Ver Peso
								</a>
								<a class="btn btn-sm btn-primary btn-icon m-2 text-white" data-bs-toggle="tooltip" data-bs-placement="top"
									title="" href="https://erp.alfabusiness.app/vaccinations/?animal_id={!! $animal->id !!}"
									data-bs-original-title="Ver" aria-label="Crear">
									<i class="text-white ti ti-eye"></i> Ver Vacunas
								</a>
								<a class="btn btn-sm btn-primary btn-icon m-2 text-white" data-bs-toggle="tooltip" data-bs-placement="top"
									title="" href="https://erp.alfabusiness.app/feeds_schedule/?animal_id={!! $animal->id !!}"
									data-bs-original-title="Ver" aria-label="Crear">
									<i class="text-white ti ti-eye"></i> Ver Horarios de Alimentación
								</a>
								<a class="btn btn-sm btn-primary btn-icon m-2 text-white" data-bs-toggle="tooltip" data-bs-placement="top"
									title="" href="https://erp.alfabusiness.app/feeds_consumption/?animal_id={!! $animal->id !!}"
									data-bs-original-title="Ver" aria-label="Crear">
									<i class="text-white ti ti-eye"></i> Ver Alimento
								</a>	
							</div>
							<div class="col-md-4">
								<div class="text-center">
									<img id="qrImage" class="img-fluid p-1" 
										 title="QR de la página actual" 
										 src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode(url()->current()) }}&size=150x150" 
										 alt="Código QR de la página">
									<br>
									<button id="shareBtn" class="btn btn-primary mt-3">Compartir QR</button>
								</div>
							</div>
                    </div>




                    <div class="mt-2 row" style="display: none">
                        <div class="col-md-12">
                            <div class="row">
                                {{ Form::open(['route' => ['store.animal.milk', $animal->id], 'method' => 'POST']) }}
                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                <div class="col-md-12 repeater">
                                    <div class="card-header">
                                        <div class="row flex-grow-1">
                                            <div class="col-md d-flex align-items-center col-6">
                                                <h5 class="card-header-title">{{ __('Milk List') }}</h5>
                                            </div>
                                            <div class="col-md-6 justify-content-between align-items-center col-6">
                                                <div class="col-md-12 d-flex align-items-center justify-content-end">
                                                    <a data-repeater-create=""
                                                        class="btn btn-primary btn-sm btn-icon add-row"
                                                        data-toggle="modal" title="{{ __('Add Row') }}"
                                                        data-target="#add-bank" data-title="{{ __('Add Row') }}">
                                                        <i class="text-white ti ti-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-border-style">
                                        <div class="table-responsive">
                                            <table class="table mb-0" data-repeater-list="milk" id="sortable-table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Date') }}</th>
                                                        <th>{{ __('Morning milk') }}</th>
                                                        <th>{{ __('Evening milk') }}</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                @if ($animal->animalmilk != '[]')
                                                    @foreach ($animal->animalmilk as $keys => $item)
                                                        <tbody class="ui-sortable" data-repeater-item>
                                                            <tr id="{{ 'form-group-container' . $keys }}">
                                                                <td class="d-none">
                                                                    {{ Form::hidden('milk[' . $keys . '][id]', $item->id, ['class' => 'form-control id']) }}
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control date"
                                                                        value="{{ $item->date }}"
                                                                        name="{{ 'milk[' . $keys . '][date]' }}">
                                                                </td>
                                                                <td width="25%" class="form-group">
                                                                    <input type="number"
                                                                        class="form-control morning_milk"
                                                                        value="{{ $item->morning_milk }}"
                                                                        name="{{ 'milk[' . $keys . '][morning_milk]' }}"
                                                                        placeholder="Enter Morning Milk">
                                                                </td>
                                                                <td width="25%" class="form-group" required>
                                                                    <input type="number"
                                                                        class="form-control evening_milk"
                                                                        value="{{ $item->evening_milk }}"
                                                                        name="{{ 'milk[' . $keys . '][evening_milk]' }}"
                                                                        placeholder="Enter Evening Milk">
                                                                </td>
                                                                <td>
                                                                    <div class="action-btn ms-2" data-repeater-delete>
                                                                        <a href="#"
                                                                            class="mx-3 bg-danger btn btn-sm align-items-center m-2">
                                                                            <i class="ti ti-trash text-white"
                                                                                data-bs-toggle="tooltip"
                                                                                data-bs-original-title="{{ __('Delete') }}"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    @endforeach
                                                @else
                                                    <tbody class="ui-sortable" data-repeater-item>
                                                        <tr>
                                                            {{ Form::hidden('milk[0][id]', null, ['class' => 'form-control id']) }}
                                                            <td>
                                                                <input type="date" class="form-control date"
                                                                    name="{{ 'milk[0][date]' }}">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control morning_milk"
                                                                    name="{{ 'milk[0][morning_milk]' }}"
                                                                    placeholder="Enter Morning Milk">
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control evening_milk"
                                                                    name="{{ 'milk[0][evening_milk]' }}"
                                                                    placeholder="Enter Evening Milk">
                                                            </td>
                                                            <td width="2%">
                                                                <div class="action-btn ms-2" data-repeater-delete>
                                                                    <a href="#"
                                                                        class="mx-3 bg-danger btn btn-sm align-items-center m-2">
                                                                        <i class="ti ti-trash text-white"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-original-title="{{ __('Delete') }}"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <!-- Fin de sección Animal Milk -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('shareBtn').addEventListener('click', function() {
    // URL actual o la del código QR
    const currentUrl = window.location.href;
    const qrUrl = document.getElementById('qrImage').src;

    // Puedes compartir el enlace actual o el de la imagen QR
    if (navigator.share) {
        navigator.share({
            title: 'QR de esta página',
            text: 'Mira el QR de esta página',
            url: currentUrl, // O reemplázalo por qrUrl para compartir la imagen
        })
        .then(() => console.log('Compartido con éxito'))
        .catch((error) => console.error('Error al compartir:', error));
    } else {
        alert('La API de compartir no está soportada en este navegador.');
    }
});
</script>
@endpush

@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('js/jquery-searchbox.js') }}"></script>

    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    JsSearchBox();
                },
                ready: function(setIndexes) {
                    // $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
        }

        $(document).on('click', '[data-repeater-delete]', function() {
            var el = $(this).parent().parent();
            var id = $(el.find('.id')).val();

            $.ajax({
                url: '{{ route('animal.milk.destroy') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function(data) {
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Success', data.success, 'success');
                    }
                },
                error: function(data) {
                    toastrs('Error', '{{ __('something went wrong please try again') }}', 'error');
                },
            });
        });
    </script>
@endpush
