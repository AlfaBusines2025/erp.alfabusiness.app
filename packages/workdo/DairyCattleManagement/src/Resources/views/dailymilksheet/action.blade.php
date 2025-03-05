@permission('dailymilksheet show')
    <div class="action-btn me-2">
        <a href="{{ route('dailymilksheet.show', \Crypt::encrypt($dailymilksheet->id)) }}"
            class="mx-3 bg-warning btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{ __('View') }}"
            data-original-title="{{ __('Detail') }}">
            <i class="text-white ti ti-eye"></i>
        </a>
    </div>
@endpermission

@permission('dailymilksheet edit')
    <div class="action-btn me-2">
        <a class="mx-3 bg-info btn btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Edit') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Daily Milk Sheet') }}"
            data-url="{{ route('dailymilksheet.edit', $dailymilksheet->id) }}"><i class="text-white ti ti-pencil"></i></a>
    </div>
@endpermission

@permission('dailymilksheet delete')
    <div class="action-btn">
        {{ Form::open(['route' => ['dailymilksheet.destroy', $dailymilksheet->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a href="#" class="mx-3 bg-danger btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="" data-bs-original-title="{{ __('Delete') }}" aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $dailymilksheet->id }}"><i class="text-white ti ti-trash"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
