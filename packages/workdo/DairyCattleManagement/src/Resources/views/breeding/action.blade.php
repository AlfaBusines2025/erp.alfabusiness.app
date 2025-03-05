@permission('breeding show')
    <div class="action-btn me-2">
        <a class="mx-3 btn bg-warning btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('View') }}" data-ajax-popup="true" data-size="md" data-title="{{ __('Breeding Details') }}"
            data-url="{{ route('breeding.show', $breeding->id) }}"><i class="text-white ti ti-eye"></i></a>
    </div>
@endpermission

@permission('breeding edit')
    <div class="action-btn me-2">
        <a class="mx-3 btn bg-info btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Edit') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Breeding') }}"
            data-url="{{ route('breeding.edit', $breeding->id) }}"><i class="text-white ti ti-pencil"></i></a>
    </div>
@endpermission

@permission('breeding delete')
    <div class="action-btn">
        {{ Form::open(['route' => ['breeding.destroy', $breeding->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a href="#" class="mx-3 bg-danger btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="" data-bs-original-title="{{ __('Delete') }}" aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $breeding->id }}"><i class="text-white ti ti-trash"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
