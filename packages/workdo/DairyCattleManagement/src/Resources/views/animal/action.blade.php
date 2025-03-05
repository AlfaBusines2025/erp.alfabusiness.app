@permission('animal show')
    <div class="action-btn me-2">
        <a href="{{ route('animal.show', \Crypt::encrypt($animal->id)) }}" class="mx-3 bg-warning btn btn-sm align-items-center"
            data-bs-toggle="tooltip" title="{{ __('View') }}" data-original-title="{{ __('Detail') }}">
            <i class="text-white ti ti-eye"></i>
        </a>
    </div>
@endpermission

@permission('animal edit')
    <div class="action-btn me-2">
        <a class="mx-3 bg-info btn btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Edit') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Animal') }}"
            data-url="{{ route('animal.edit', $animal->id) }}"><i class="text-white ti ti-pencil"></i></a>
    </div>
@endpermission

@permission('animal delete')
    <div class="action-btn">
        {{ Form::open(['route' => ['animal.destroy', $animal->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a href="#" class="mx-3 bg-danger btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="" data-bs-original-title="{{ __('Delete') }}" aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $animal->id }}"><i class="text-white ti ti-trash"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
