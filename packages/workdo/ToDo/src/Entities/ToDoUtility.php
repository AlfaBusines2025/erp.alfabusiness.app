<?php

namespace Workdo\ToDo\Entities;

use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToDoUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    public static function defaultdata($company_id = null, $workspace_id = null)
    {
        $stages = [
            1 => 'All',
            2 => 'Pending',
            3 => 'Upcoming',
            4 => 'Completed',
        ];
        if ($company_id == Null) {
            $companys = User::where('type', 'company')->get();
            foreach ($companys as $company) {
                $WorkSpaces = WorkSpace::where('created_by', $company->id)->get();
                foreach ($WorkSpaces as $WorkSpace) {
                    $arrStages = TodoStage::where(['workspace_id' => $WorkSpace->id, 'created_by' => $company->id])->orderBy('order')->pluck('name', 'id')->all();
                    if (empty($arrStages)) {
                        foreach ($stages as $key => $stage) {
                            $obj               = new TodoStage();
                            $obj->workspace_id = $WorkSpace->id;
                            $obj->created_by = $company->id;
                            $obj->name     = $stage;
                            $obj->color    = '#77b6ea';
                            $obj->order    = $key - 1;
                            $obj->complete = 0;
                            $obj->save();
                        }
                    }
                }
            }
        } elseif ($workspace_id == Null) {
            $company = User::where('type', 'company')->where('id', $company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by', $company->id)->get();
            foreach ($WorkSpaces as $WorkSpace) {

                $arrStages = TodoStage::where(['workspace_id' => $WorkSpace->id, 'created_by' => $company->id])->orderBy('order')->pluck('name', 'id')->all();

                if (empty($arrStages)) {
                    foreach ($stages as $key => $stage) {
                        $obj           = new TodoStage();
                        $obj->name     = $stage;
                        $obj->color    = '#77b6ea';
                        $obj->order    = $key - 1;
                        $obj->complete = 0;
                        $obj->created_by = $company->id;
                        $obj->workspace_id = $WorkSpace->id;
                        $obj->save();
                    }
                }
            }
        } else {
            $company = User::where('type', 'company')->where('id', $company_id)->first();
            $WorkSpace = WorkSpace::where('created_by', $company->id)->where('id', $workspace_id)->first();

            $arrStages = TodoStage::where(['workspace_id' => $WorkSpace->id, 'created_by' => $company->id])->orderBy('order')->pluck('name', 'id')->all();

            if (empty($arrStages)) {
                foreach ($stages as $key => $stage) {
                    $obj           = new TodoStage();
                    $obj->name     = $stage;
                    $obj->color    = '#77b6ea';
                    $obj->order    = $key - 1;
                    $obj->complete = 0;
                    $obj->created_by = $company->id;
                    $obj->workspace_id = $WorkSpace->id;
                    $obj->save();
                }
            }
        }
    }
}
