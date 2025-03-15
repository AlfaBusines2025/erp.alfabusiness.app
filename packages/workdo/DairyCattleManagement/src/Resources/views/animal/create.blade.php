@php
    // Asignar los valores originales directamente en la vista (puedes usarlo así
    // o traer estos valores de tu controlador si prefieres).
    $healthStatusOptions = [
        0 => 'Healthy',
        1 => 'Injured',
        2 => 'Sick'
    ];
    $breedingOptions = [
        0 => 'Ready for Brending',
        1 => 'Embarazada',
        2 => 'Not ready'
    ];

    // Asignar las traducciones (manteniendo las mismas claves)
    $translatedHealthStatusOptions = [
        0 => 'Saludable',
        1 => 'Herido',
        2 => 'Enfermo'
    ];

    $translatedBreedingOptions = [
        0 => 'Listo para reproducción',
        1 => 'Embarazada',
        2 => 'No listo'
    ];
@endphp

{{ Form::open(['url' => 'animal', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="tab-content tab-bordered">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
            <div class="row">
                {{-- Nombre --}}
                <div class="col-6 form-group">
                    {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('name', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Name',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Especie --}}
                <div class="col-6 form-group">
                    {{ Form::label('species', __('Species'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('species', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Species',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Raza --}}
                <div class="col-6 form-group">
                    {{ Form::label('breed', __('Breed'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('breed', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Breed',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Fecha de nacimiento --}}
                <div class="col-6 form-group">
                    {{ Form::label('birth_date', __('Date Of Birth'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::date('birth_date', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Date Of Birth',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Género --}}
                <div class="col-6 form-group">
                    {{ Form::label('gender', __('Gender'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('gender', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Gender',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Estado de salud --}}
                <div class="col-6 form-group">
                    {{ Form::label('health_status', __('Health Status'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::select('health_status', $translatedHealthStatusOptions, null, [
                        'class' => 'form-control',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Peso --}}
                <div class="col-6 form-group">
                    {{ Form::label('weight', __('Weight'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::number('weight', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Weight',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Estado reproductivo --}}
                <div class="col-6 form-group">
                    {{ Form::label('breeding', __('Breeding Status'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::select('breeding', $translatedBreedingOptions, null, [
                        'class' => 'form-control',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Notas / comentarios --}}
                <div class="col-6 form-group">
                    {{ Form::label('note', __('Notes/Comments'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::text('note', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Notes',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Imagen --}}
                <div class="col-6 form-group">
                    {{ Form::label('image', __('Image'), ['class' => 'form-label']) }}<x-required></x-required>
                    {{ Form::file('image', [
                        'class' => 'form-control',
                        'required' => 'required'
                    ]) }}
                </div>

                {{-- Select Owner (tabla customers) --}}
                <div class="col-6 form-group">
                    {{ Form::label('nombre_propietario_animal', __('Owner Name'), ['class' => 'form-label']) }}
                    {{ Form::select('nombre_propietario_animal', $customers, null, [
                        'class'       => 'form-control',
                        'required'    => 'required',
                        'placeholder' => __('Select Owner'),
                        'id'          => 'nombre_propietario_animal'
                    ]) }}
                </div>

                {{-- Diet Notes --}}
                <div class="col-6 form-group">
                    {{ Form::label('notas_dieta_animal', __('Diet Notes'), ['class' => 'form-label']) }}
                    {{ Form::text('notas_dieta_animal', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Diet Notes'
                    ]) }}
                </div>

                {{-- Stable Number --}}
                <div class="col-6 form-group">
                    {{ Form::label('numero_pesebrera_animal', __('Stable Number'), ['class' => 'form-label']) }}
                    {{ Form::number('numero_pesebrera_animal', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Stable Number'
                    ]) }}
                </div>

                {{-- Stable Facilities --}}
                <div class="col-6 form-group">
                    {{ Form::label('instalaciones_pesebrera_animal', __('Stable Facilities'), ['class' => 'form-label']) }}
                    {{ Form::select('instalaciones_pesebrera_animal', [
                        'VERDES'     => 'VERDES',
                        'CAFES'      => 'CAFES',
                        'ESCUELITA'  => 'ESCUELITA',
                        'PREMIUM'    => 'PREMIUM',
                        'POTREROS'   => 'POTREROS',
                        'PORTATILES' => 'PORTATILES'
                    ], null, [
                        'class' => 'form-control',
                        'placeholder' => 'Select Facility'
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <button type="submit" class="btn btn-primary">{{ __('Create') }}</button>
</div>
{{ Form::close() }}


