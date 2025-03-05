{{ Form::model($health, array('route' => array('health.update', $health->id), 'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'needs-validation','novalidate')) }}
    <div class="modal-body">
        <div class="tab-content tab-bordered">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                <div class="row">
                    <div class="col-6 form-group">
                        {{ Form::label('animal_id', __('Animal Name'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::select('animal_id', $animal, null, array('class' => 'form-control','placeholder'=>'Enter Name','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('veterinarian', __('Veterinarian'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('veterinarian', null, array('class' => 'form-control','placeholder'=>'Enter Veterinarian','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('duration', __('Duration'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::select('duration', $durations, null, ['id' => 'duration', 'class' => 'form-control','placeholder'=>'Select Duration','required'=>'required']) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('date', __('Date'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::date('date', null, ['id' => 'date', 'class' => 'form-control','placeholder'=>'Enter Date','required'=>'required']) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('checkup_date', __('Next Checkup Date'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::date('checkup_date', null, ['id' => 'checkup_date', 'class' => 'form-control','placeholder'=>'Enter Next Checkup Date','required'=>'required']) }}
                    </div>

                    <div class="col-6 form-group">
                        {{ Form::label('diagnosis', __('Diagnosis'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('diagnosis', null, array('class' => 'form-control','placeholder'=>'Enter Diagnosis','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('treatment', __('Treatment'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::text('treatment', null, array('class' => 'form-control','placeholder'=>'Enter Treatment','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('price', __('price'),['class'=>'form-label']) }}<x-required></x-required>
                        {{ Form::number('price', null, array('class' => 'form-control','placeholder'=>'Enter price','required'=>'required')) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
    </div>

{{ Form::close() }}


<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#duration').on('change', function(){
            var duration = parseInt($('#duration').val());
            console.log(duration);
            var startDate = new Date($('#date').val());
            var checkupDate = new Date(startDate);
            checkupDate.setMonth(checkupDate.getMonth() + duration);

            if (startDate.getDate() !== checkupDate.getDate()) {
                checkupDate = new Date(checkupDate.getFullYear(), checkupDate.getMonth() + 1, 0);
            }

            $('#checkup_date').val(checkupDate.toISOString().split('T')[0]);
        });

        $('#date').on('change', function(){
            var duration = parseInt($('#duration').val());
            console.log(duration);
            var startDate = new Date($('#date').val());
            var checkupDate = new Date(startDate);
            checkupDate.setMonth(checkupDate.getMonth() + duration);

            if (startDate.getDate() !== checkupDate.getDate()) {
                checkupDate = new Date(checkupDate.getFullYear(), checkupDate.getMonth() + 1, 0);
            }

            $('#checkup_date').val(checkupDate.toISOString().split('T')[0]);
        });
    });
</script>


