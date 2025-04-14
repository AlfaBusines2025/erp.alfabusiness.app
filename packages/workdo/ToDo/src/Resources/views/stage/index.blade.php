@extends('layouts.main')

@section('page-title')
    {{ __('Task Stages') }}
@endsection
@section('page-breadcrumb')
    {{ __('Task Stages') }}
@endsection

@section('content')
    <div class="row" id="task-stages-settings">
        <div class="col-sm-3">
            @include('to-do::layouts.setup')
        </div>
        <div class="col-sm-9">
            <div class="card task-stages" data-value="{{ json_encode($stages) }}">
                <div class="card-header">
                    <div class="row">
                        <div class="col-11">
                            <h5 class="">
                                {{ __('Task Stages') }}
                            </h5>
                            <small class="">{{ __('System will consider the last stage as a completed / done project or task status.') }}</small>
                        </div>
                        <div class="col-1 text-end">
                            @permission('task stage manage')
                                <button data-repeater-create type="button" class="btn-submit btn btn-sm btn-primary btn-icon"
                                    data-toggle="tooltip" title="{{ __('Add') }}">
                                    <i class="ti ti-plus"></i>
                                </button>
                            @endpermission
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="post" action="{{ route('stage.store') }}">
                        @csrf
                        <table class="table table-hover" data-repeater-list="stages">
                            <thead>
                                <th>
                                    <div data-toggle="tooltip" data-placement="left"
                                        data-title="{{ __('Drag Stage to Change Order') }}" data-original-title=""
                                        title="">
                                        <i class="fas fa-crosshairs"></i>
                                    </div>
                                </th>
                                <th>{{ __('Color') }}</th>
                                <th>{{ __('Name') }}</th>
                                @permission('task stage delete')
                                    <th class="text-right">{{ __('Action') }}</th>
                                @endpermission
                            </thead>
                            <tbody>
                                <tr data-repeater-item>
                                    <td><i class="fas fa-crosshairs sort-handler"></i></td>
                                    <td>
                                        <input type="color" name="color" class="field_type">
                                    </td>
                                    <td>
                                        <input type="hidden" name="id" id="id" />
                                        <input type="text" name="name" class="form-control mb-0 field_type" placeholder="Enter Name"
                                            @if (!auth()->user()->isAbleTo('task stage edit')) readonly @endif required />
                                    </td>
                                    @permission('task stage delete')
                                        <td class="text-right">
                                            <a data-repeater-delete
                                                class=" action-btn btn-danger delete-icon btn btn-sm d-inline-flex align-items-center"
                                                data-toggle="tooltip" title="{{ __('Delete') }}">
                                                <i class="ti ti-trash text-white"></i>
                                            </a>
                                        </td>
                                    @endpermission
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="text-sm col-6 pt-2">
                                {{ __('Note : You can easily change order of Task stage using drag & drop.') }}
                            </div>
                            <div class="col-6 text-end pt-2">
                                <button class="btn-submit btn btn-primary" type="submit">{{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('packages/workdo/ToDo/src/Resources/assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('packages/workdo/ToDo/src/Resources/assets/js/repeater.js') }}"></script>
    <script src="{{ asset('packages/workdo/ToDo/src/Resources/assets/js/colorPick.js') }}"></script>
    <script src="{{ asset('assets/js/pages/wow.min.js') }}"></script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>

    <script>
        $(document).ready(function() {
            var $dragAndDrop = $("body .task-stages tbody").sortable({
                handle: '.sort-handler'
            });

            var $repeater = $('.task-stages').repeater({
                initEmpty: true,
                defaultValues: {},
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('{{ __('Are you sure ?') }}')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });

            var value = $(".task-stages").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }

            var $repeaterItems = $('[data-repeater-item]');
            var eleCount = $repeaterItems.length;

            $repeaterItems.each(function(index) {
                if (index < 4) {
                    $(this).find('.field_type').prop('readonly', true);
                    $(this).find('.delete-icon').remove();
                }
            });

            var type = window.location.hash.substr(1);
            $('.list-group-item').removeClass('active');
            $('.list-group-item').removeClass('text-primary');
            if (type != '') {
                $('a[href="#' + type + '"]').addClass('active').removeClass('text-primary');
            } else {
                $('.list-group-item:eq(0)').addClass('active').removeClass('text-primary');
            }
        });
    </script>
@endpush
