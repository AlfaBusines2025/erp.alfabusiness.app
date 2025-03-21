@extends('layouts.admin')
@section('page-title')
    {{__('Manage Employee  Salary List')}}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="nav-tabs">
                <div class="col-lg-12 our-system">
                    <div class="row">
                        <ul class="nav nav-tabs my-4">
                            <li>
                                <a data-toggle="tab" href="#salary" class="active">{{__('Salary')}}</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#allowance" class="">{{__('Allowance')}}</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#commission" class="">{{__('Commission')}}</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#loan" class="">{{__('Loan')}}</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#saturation-deduction" class="">{{__('Saturation Deduction')}}</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#other-payment" class="">{{__('Other Payment')}}</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#overtime" class="">{{__('Overtime')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="salary" class="tab-pane in active">
                        <div class="card">
                            <div class="card-body">
                                {{ Form::model($employee, array('route' => array('employee.salary.update', $employee->id), 'method' => 'POST')) }}
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('salary_type', __('Payslip Type*'),['class'=>'form-label']) }}
                                            {{ Form::select('salary_type',$payslip_type,null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('salary', __('Salary'),['class'=>'form-label']) }}
                                            {{ Form::number('salary',null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                </div>
                                @permission('Create Set Salary')
                                    <div class="row">
                                        <div class="col-12 text-right mt-1">
                                            <input type="submit" value="{{__('Save Change')}}" class="btn-create badge-blue">
                                        </div>
                                    </div>
                                @endpermission
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    <div id="allowance" class="tab-pane">
                        <div class="card">
                            <div class="card-body">
                                {{Form::open(array('url'=>'allowance','method'=>'post'))}}
                                @csrf
                                {{ Form::hidden('employee_id',$employee->id, array()) }}
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('allowance_option', __('Allowance Options*'),['class'=>'form-label']) }}
                                            {{ Form::select('allowance_option',$allowance_options,null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}
                                            {{ Form::text('title',null, array('class' => 'form-control','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                                            {{ Form::number('amount',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01')) }}
                                        </div>
                                    </div>
                                </div>
                                @permission('Create Allowance')
                                    <div class="row">
                                        <div class="col-12 text-right mt-1">
                                            <input type="submit" value="{{__('Save Change')}}" class="btn-create badge-blue">
                                        </div>
                                    </div>
                                @endpermission
                                {{Form::close()}}
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0" id="allowance-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{__('Employee Name')}}</th>
                                            <th>{{__('Allownace Option')}}</th>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('Amount')}}</th>
                                             <th>{{ __('Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($allowances as $allowance)
                                            <tr>
                                                <td>{{ $allowance->employee()->name }}</td>
                                                <td>{{ $allowance->allowance_option()->name }}</td>
                                                <td>{{ $allowance->title }}</td>
                                                <td>{{  \Auth::user()->priceFormat($allowance->amount) }}</td>
                                                @permission('Delete Set Salary')
                                                    <td class="d-flex">
                                                        @permission('Edit Allowance')
                                                            <a  data-url="{{ URL::to('allowance/'.$allowance->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Allowance')}}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil"></i></a>
                                                        @endpermission
                                                        @permission('Delete Allowance')
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['allowance.destroy', $allowance->id], 'id' => 'delete-form-' . $allowance->id]) !!}
                                                        <a href="#!"
                                                            class="action-btn btn-danger me-1 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="{{ __('Delete') }}">
                                                            <i class="ti ti-trash"></i></a>
                                                        {!! Form::close() !!}

                                                        @endpermission
                                                    </td>
                                                @endpermission
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="commission" class="tab-pane">
                        <div class="card">
                            <div class="card-body">
                                {{Form::open(array('url'=>'commission','method'=>'post'))}}
                                @csrf
                                {{ Form::hidden('employee_id',$employee->id, array()) }}
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}
                                            {{ Form::text('title',null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                                            {{ Form::number('amount',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01')) }}
                                        </div>
                                    </div>
                                </div>
                                @permission('Create Commission')
                                    <div class="row">
                                        <div class="col-12 text-right mt-1">
                                            <input type="submit" value="{{__('Save Change')}}" class="btn-create badge-blue">
                                        </div>
                                    </div>
                                @endpermission
                                {{Form::close()}}
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0" id="commission-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{__('Employee Name')}}</th>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('Amount')}}</th>
                                             <th>{{ __('Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($commissions as $commission)
                                            <tr>
                                                <td>{{ $commission->employee()->name }}</td>
                                                <td>{{ $commission->title }}</td>
                                                <td>{{  \Auth::user()->priceFormat($commission->amount )}}</td>

                                                <td class="d-flex">
                                                    @permission('Edit Commission')
                                                        <a  data-url="{{ URL::to('commission/'.$commission->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Commission')}}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil"></i></a>
                                                    @endpermission
                                                    @permission('Delete Commission')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['commission.destroy', $commission->id], 'id' => 'delete-form-' . $commission->id]) !!}
                                                    <a href="#!"
                                                        class="action-btn btn-danger me-1 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash"></i></a>
                                                    {!! Form::close() !!}

                                                    @endpermission
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="loan" class="tab-pane">
                        <div class="card">
                            <div class="card-body">
                                {{Form::open(array('url'=>'loan','method'=>'post'))}}
                                @csrf
                                {{ Form::hidden('employee_id',$employee->id, array()) }}
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('loan_option', __('Loan Options*'),['class'=>'form-label']) }}
                                            {{ Form::select('loan_option',$loan_options,null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}
                                            {{ Form::text('title',null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('amount', __('Loan Amount'),['class'=>'form-label']) }}
                                            {{ Form::number('amount',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01')) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}
                                            {{ Form::text('start_date',null, array('class' => 'form-control ','id'=>'data_picker4','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('end_date', __('End Date'),['class'=>'form-label']) }}
                                            {{ Form::text('end_date',null, array('class' => 'form-control ','id'=>'data_picker3','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('reason', __('Reason'),['class'=>'form-label']) }}
                                            {{ Form::textarea('reason',null, array('class' => 'form-control','rows'=>1,'required'=>'required')) }}
                                        </div>
                                    </div>
                                </div>

                                @permission('Create Loan')
                                    <div class="row">
                                        <div class="col-12 text-right mt-1">
                                            <input type="submit" value="{{__('Save Change')}}" class="btn-create badge-blue">
                                        </div>
                                    </div>
                                @endpermission
                                {{Form::close()}}

                                <hr>

                                <div class="table-responsive">
                                    <table class="table table-striped mb-0" id="loan-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{__('employee')}}</th>
                                            <th>{{__('Loan Options')}}</th>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('Loan Amount')}}</th>
                                            <th>{{__('Start Date')}}</th>
                                            <th>{{__('End Date')}}</th>
                                             <th>{{ __('Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($loans as $loan)
                                            <tr>
                                                <td>{{ $loan->employee()->name }}</td>
                                                <td>{{ $loan->loan_option()->name }}</td>
                                                <td>{{ $loan->title }}</td>
                                                <td>{{  \Auth::user()->priceFormat($loan->amount) }}</td>
                                                <td>{{  \Auth::user()->dateFormat($loan->start_date) }}</td>
                                                <td>{{ \Auth::user()->dateFormat( $loan->end_date) }}</td>

                                                <td class="d-flex">
                                                    @permission('Edit Loan')
                                                        <a  data-url="{{ URL::to('loan/'.$loan->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Loan')}}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil"></i></a>
                                                    @endpermission
                                                    @permission('Delete Loan')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['loan.destroy', $loan->id], 'id' => 'delete-form-' . $loan->id]) !!}
                                                    <a href="#!"
                                                        class="action-btn btn-danger me-1 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash"></i></a>
                                                    {!! Form::close() !!}

                                                    @endpermission
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="saturation-deduction" class="tab-pane">
                        <div class="card">
                            <div class="card-body">
                                {{Form::open(array('url'=>'saturationdeduction','method'=>'post'))}}
                                @csrf
                                {{ Form::hidden('employee_id',$employee->id, array()) }}
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('deduction_option', __('Deduction Options*'),['class'=>'form-label']) }}
                                            {{ Form::select('deduction_option',$deduction_options,null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}
                                            {{ Form::text('title',null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                                            {{ Form::number('amount',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01')) }}
                                        </div>
                                    </div>
                                </div>
                                @permission('Create Saturation Deduction')
                                    <div class="row">
                                        <div class="col-12 text-right mt-1">
                                            <input type="submit" value="{{__('Save Change')}}" class="btn-create badge-blue">
                                        </div>
                                    </div>
                                @endpermission
                                {{Form::close()}}

                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0" id="saturation-deduction-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{__('Employee Name')}}</th>
                                            <th>{{__('Deduction Option')}}</th>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('Amount')}}</th>
                                             <th>{{ __('Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($saturationdeductions as $saturationdeduction)
                                            <tr>

                                                <td>{{ $saturationdeduction->employee()->name }}</td>
                                                <td>{{ $saturationdeduction->deduction_option()->name }}</td>
                                                <td>{{ $saturationdeduction->title }}</td>
                                                <td>{{ \Auth::user()->priceFormat( $saturationdeduction->amount )}}</td>

                                                <td class="d-flex">
                                                    @permission('Edit Saturation Deduction')
                                                        <a  data-url="{{ URL::to('saturationdeduction/'.$saturationdeduction->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Saturation Deduction')}}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil"></i></a>
                                                    @endpermission
                                                    @permission('Delete Saturation Deduction')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['saturationdeduction.destroy', $saturationdeduction->id], 'id' => 'delete-form-' . $saturationdeduction->id]) !!}
                                                    <a href="#!"
                                                        class="action-btn btn-danger me-1 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash"></i></a>
                                                    {!! Form::close() !!}

                                                    @endpermission
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="other-payment" class="tab-pane">
                        <div class="card">
                            <div class="card-body">
                                {{Form::open(array('url'=>'otherpayment','method'=>'post'))}}
                                @csrf
                                {{ Form::hidden('employee_id',$employee->id, array()) }}
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('title', __('Title'),['class'=>'form-label']) }}
                                            {{ Form::text('title',null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                                            {{ Form::number('amount',null, array('class' => 'form-control ','required'=>'required' ,'step'=>'0.01')) }}
                                        </div>
                                    </div>
                                </div>

                                @permission('Create Other Payment')
                                    <div class="row">
                                        <div class="col-12 text-right mt-1">
                                            <input type="submit" value="{{__('Save Change')}}" class="btn-create badge-blue">
                                        </div>
                                    </div>
                                @endpermission
                                {{Form::close()}}


                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0" id="other-payment-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{__('employee')}}</th>
                                            <th>{{__('Title')}}</th>
                                            <th>{{__('Amount')}}</th>
                                             <th>{{ __('Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($otherpayments as $otherpayment)
                                            <tr>
                                                <td>{{ $otherpayment->employee()->name }}</td>
                                                <td>{{ $otherpayment->title }}</td>
                                                <td>{{  \Auth::user()->priceFormat($otherpayment->amount )}}</td>

                                                <td class="d-flex">
                                                    @permission('Edit Other Payment')
                                                        <a  data-url="{{ URL::to('otherpayment/'.$otherpayment->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Other Payment')}}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil"></i></a>
                                                    @endpermission
                                                    @permission('Delete Other Payment')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['otherpayment.destroy', $otherpayment->id], 'id' => 'delete-form-' . $otherpayment->id]) !!}
                                                    <a href="#!"
                                                        class="action-btn btn-danger me-1 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash"></i></a>
                                                    {!! Form::close() !!}

                                                    @endpermission
                                                </td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="overtime" class="tab-pane">
                        <div class="card">
                            <div class="card-body">
                                {{Form::open(array('url'=>'overtime','method'=>'post'))}}
                                @csrf
                                {{ Form::hidden('employee_id',$employee->id, array()) }}
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('title', __('Overtime Title*'),['class'=>'form-label']) }}
                                            {{ Form::text('title',null, array('class' => 'form-control ','required'=>'required')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('number_of_days', __('Number of days'),['class'=>'form-label']) }}
                                            {{ Form::number('number_of_days',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('hours', __('Hours'),['class'=>'form-label']) }}
                                            {{ Form::number('hours',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01')) }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('rate', __('Rate'),['class'=>'form-label']) }}
                                            {{ Form::number('rate',null, array('class' => 'form-control ','required'=>'required','step'=>'0.01')) }}
                                        </div>
                                    </div>
                                </div>
                                @permission('Create Overtime')
                                    <div class="row">
                                        <div class="col-12 text-right mt-1">
                                            <input type="submit" value="{{__('Save Change')}}" class="btn-create badge-blue">
                                        </div>
                                    </div>
                                @endpermission
                                {{Form::close()}}

                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0" id="overtime-dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{__('Employee Name')}}</th>
                                            <th>{{__('Overtime Title')}}</th>
                                            <th>{{__('Number of days')}}</th>
                                            <th>{{__('Hours')}}</th>
                                            <th>{{__('Rate')}}</th>

                                             <th>{{ __('Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($overtimes as $overtime)
                                            <tr>
                                                <td>{{ $overtime->employee()->name }}</td>
                                                <td>{{ $overtime->title }}</td>
                                                <td>{{ $overtime->number_of_days }}</td>
                                                <td>{{ $overtime->hours }}</td>
                                                <td>{{ \Auth::user()->priceFormat( $overtime->rate) }}</td>

                                                <td class="d-flex">
                                                    @permission('Edit Overtime')
                                                        <a  data-url="{{ URL::to('overtime/'.$overtime->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit OverTime')}}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil"></i></a>
                                                    @endpermission
                                                    @permission('Delete Overtime')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['overtime.destroy', $overtime->id], 'id' => 'delete-form-' . $overtime->id]) !!}
                                                    <a href="#!"
                                                        class="action-btn btn-danger me-1 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        title="{{ __('Delete') }}">
                                                        <i class="ti ti-trash"></i></a>
                                                    {!! Form::close() !!}

                                                    @endpermission
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('script-page')
    <script type="text/javascript">

        $(document).ready(function () {
            var d_id = $('#department_id').val();
            var designation_id = '{{ $employee->designation_id }}';
            getDesignation(d_id);


            $("#allowance-dataTable").dataTable({
                "columnDefs": [
                    {"sortable": false, "targets": [1]}
                ]
            });

            $("#commission-dataTable").dataTable({
                "columnDefs": [
                    {"sortable": false, "targets": [1]}
                ]
            });

            $("#loan-dataTable").dataTable({
                "columnDefs": [
                    {"sortable": false, "targets": [1]}
                ]
            });

            $("#saturation-deduction-dataTable").dataTable({
                "columnDefs": [
                    {"sortable": false, "targets": [1]}
                ]
            });

            $("#other-payment-dataTable").dataTable({
                "columnDefs": [
                    {"sortable": false, "targets": [1]}
                ]
            });

            $("#overtime-dataTable").dataTable({
                "columnDefs": [
                    {"sortable": false, "targets": [1]}
                ]
            });


        });

        $(document).on('change', 'select[name=department_id]', function () {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        function getDesignation(did) {
            $.ajax({
                url: '{{route('employee.json')}}',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#designation_id').empty();
                    $('#designation_id').append('<option value="">{{__('Select any Designation')}}</option>');
                    $.each(data, function (key, value) {
                        var select = '';
                        if (key == '{{ $employee->designation_id }}') {
                            select = 'selected';
                        }

                        $('#designation_id').append('<option value="' + key + '"  ' + select + '>' + value + '</option>');
                    });
                }
            });
        }

    </script>
@endpush
