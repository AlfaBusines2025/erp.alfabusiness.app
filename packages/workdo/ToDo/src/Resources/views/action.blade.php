@permission('todo complete')
    <div class="action-btn ms-2 disabled-form-switch">
        @if ($todo->status == $todo->todo_stage_id)
            <span class="action-item" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Completed') }}">
                <div class="@if ($todo->status == $todo->todo_stage_id)mx-3 btn btn-sm bg-success align-items-center @endif">
                    <i class="ti ti-check text-white"></i>
                </div>
            </span>
        @else
            <a href="#" class="mx-3 btn btn-sm" data-size="md" data-url="{{ route('to-do.status', $todo->id) }}"
               data-ajax-popup="true" class="action-item"
               data-title="{{ __('Mark Completed?') }}"
               data-bs-toggle="tooltip" data-bs-placement="top"
               data-bs-original-title="{{ __('Mark Completed?') }}" style="@if ($todo->status != $todo->todo_stage_id) background: #0069d9; @endif">
                <i class="ti ti-clock text-white"></i>
            </a>
        @endif
    </div>
@endpermission
@permission('todo show')
    <div class="action-btn ms-2">
        <a class="mx-3 btn btn-sm bg-warning align-items-center" data-ajax-popup="true" data-size="lg"
            title="{{ __('View') }}" data-title="{{ __('To Do Detail') }}" data-bs-toggle="tooltip" data-url="{{ route('to-do.show', $todo->id) }}">
            <i class="ti ti-eye text-white"></i>
        </a>
    </div>
@endpermission
@permission('todo edit')
    <div class="action-btn ms-2">
        <a class="mx-3 btn bg-info btn-sm align-items-center" data-ajax-popup="true" data-size="md"
            title="{{ __('Edit') }}" data-title="{{ __('Edit To Do') }}" data-bs-toggle="tooltip" data-url="{{ route('to-do.edit', $todo->id) }}">
            <i class="ti ti-pencil text-white"></i>
        </a>
    </div>
@endpermission
@permission('todo delete')
    <div class="action-btn ms-2">
        {!! Form::open(['method' => 'DELETE', 'route' => ['to-do.destroy', $todo->id], 'id' => 'delete-form-' . $todo->id]) !!}
        <a class="mx-3 btn bg-danger btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="{{ __('Delete') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"><i
                class="ti ti-trash text-white text-white"></i></a>
        {!! Form::close() !!}
    </div>
@endpermission
