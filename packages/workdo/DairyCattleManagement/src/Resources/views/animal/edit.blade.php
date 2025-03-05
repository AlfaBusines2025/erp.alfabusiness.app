{{ Form::model($animal, array('route' => array('animal.update', $animal->id), 'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="tab-content tab-bordered">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                <div class="row">
                    <div class="col-6 form-group">
                        {{ Form::label('name', __('Name'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('name', null, array('class' => 'form-control','placeholder'=>'Enter Name','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('species', __('Species'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('species', null, array('class' => 'form-control','placeholder'=>'Enter Species','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('breed', __('Breed'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('breed', null, array('class' => 'form-control','placeholder'=>'Enter Breed','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('birth_date', __('Date Of Birth'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::date('birth_date', null, array('class' => 'form-control','placeholder'=>'Enter Date Of Birth','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('gender', __('Gender'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('gender', null, array('class' => 'form-control','placeholder'=>'Enter Gender','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('health_status', __('Health Status'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::select('health_status', $healthStatusOptions, null, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('weight', __('Weight'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::number('weight', null, array('class' => 'form-control','placeholder'=>'Enter Weight','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('breeding', __('Breeding Status'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::select('breeding', $breedingOptions, null, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('note', __('Notes/Comments'), ['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('note', null, array('class' => 'form-control','placeholder'=>'Enter Note','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('image', __('Image'), ['class'=>'form-label']) }}<x-required></x-required>
                        <input type="file" class="form-control file" name="image" id="image"
                        data-filename="image_update"
                        onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                        @php
                            if (check_file($animal->image) == false) {
                                $path = asset('packages/workdo/DairyCattleManagement/src/Resources/assets/image/img01.jpg');
                            } else {
                                $path = get_file($animal->image);
                            }
                        @endphp
                        <img class="mt-3" id="blah" src="{{ $path }}" alt="your image" width="100" height="100" />
                    </div>

                    <!-- Nuevos campos -->
                    <div class="col-6 form-group">
                        {{ Form::label('nombre_propietario_animal', __('Owner Name'), ['class'=>'form-label']) }}
                        {{ Form::text('nombre_propietario_animal', null, array('class' => 'form-control','placeholder'=>'Enter Owner Name')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('notas_dieta_animal', __('Diet Notes'), ['class'=>'form-label']) }}
                        {{ Form::text('notas_dieta_animal', null, array('class' => 'form-control','placeholder'=>'Enter Diet Notes')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('numero_pesebrera_animal', __('Stable Number'), ['class'=>'form-label']) }}
                        {{ Form::number('numero_pesebrera_animal', null, array('class' => 'form-control','placeholder'=>'Enter Stable Number')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('instalaciones_pesebrera_animal', __('Stable Facilities'), ['class'=>'form-label']) }}
                        {{ Form::select('instalaciones_pesebrera_animal', [
                            'VERDES'      => 'VERDES',
                            'CAFES'       => 'CAFES',
                            'ESCUELITA'   => 'ESCUELITA',
                            'PREMIUM'     => 'PREMIUM',
                            'POTREROS'    => 'POTREROS',
                            'PORTATILES'  => 'PORTATILES'
                        ], null, array('class' => 'form-control','placeholder'=>'Select Facility')) }}
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
    </div>
{{ Form::close() }}
