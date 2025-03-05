@permission('sales-distribution edit')
    <div class="action-btn me-2">
        <a class="mx-3 btn bg-info btn-sm align-items-center" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Edit') }}" data-ajax-popup="true" data-size="md" data-title="{{ __('Edit Birth Records') }}"
            data-url="{{ route('birth_records.edit', $birth_record->id) }}"><i class="text-white ti ti-pencil"></i></a>
    </div>
@endpermission

@permission('sales-distribution delete')
    <div class="action-btn">
        {{ Form::open(['route' => ['birth_records.destroy', $birth_record->id], 'class' => 'm-0']) }}
        @method('DELETE')
        <a href="#" class="mx-3 bg-danger btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip"
            title="" data-bs-original-title="{{ __('Delete') }}" aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
            data-confirm-yes="delete-form-{{ $birth_record->id }}"><i class="text-white ti ti-trash"></i></a>
        {{ Form::close() }}
    </div>
@endpermission
