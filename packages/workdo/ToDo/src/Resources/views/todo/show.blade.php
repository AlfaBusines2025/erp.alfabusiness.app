<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-bordered ">
            <tr role="row">
                <th>{{ __('Module') }}</th>
                <td>{{ !empty($task->sub_module) ? $task->sub_module : '----' }}</td>
            </tr>
            <tr role="row">
                <th>{{ __('Title') }}</th>
                <td>{{ !empty($task->title) ? $task->title : '' }}</td>
            </tr>
            <tr>
                <th>{{ __('Description ') }}</th>
                <td class="text-wrap text-break">{{ !empty($task->description) ? $task->description : '' }}</td>
            </tr>
            <tr>
                <th>{{ __('Start Date') }}</th>
                <td>{{ company_date_formate($task->start_date) }}</td>
            </tr>
            <tr>
                <th>{{ __('End Date') }}</th>
                <td>{{ company_date_formate($task->due_date) }}</td>
            </tr>
            <tr>
                <th>{{ __('Assigned To') }}</th>
                <td>
                    {{Workdo\ToDo\Entities\ToDo::getTeams($task->assigned_to)}}
                </td>
            </tr>
            <tr>
                <th>{{ __('Assigned By') }}</th>
                <td>{{ $task->assignedByUser->name }}</td>
            </tr>
            <tr>
                <th>{{ __('Priority') }}</th>
                <td>{{ $task->priority }}</td>
            </tr>
        </table>
    </div>
</div>
