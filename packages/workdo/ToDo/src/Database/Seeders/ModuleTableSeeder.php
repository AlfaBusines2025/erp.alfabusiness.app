<?php

namespace Workdo\ToDo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $todo_module_fileds = [];

        $todo_module_fileds['Base'] =  ['Proposal', 'Invoice'];
        $todo_module_fileds['Account'] = ['Customer', 'Vendor', 'Bill'];
        $todo_module_fileds['Assets'] =  ['Assets'];
        $todo_module_fileds['Contract'] =  ['Contract'];
        $todo_module_fileds['Hrm'] =  ['Employee'];
        $todo_module_fileds['Lead'] =  ['Lead', 'Deal'];
        $todo_module_fileds['Performance'] =  ['Goal Tracking'];
        $todo_module_fileds['Pos'] =  ['Warehouse', 'Purchase'];
        $todo_module_fileds['ProductService'] =  ['Product & Service'];
        $todo_module_fileds['Retainer'] =  ['Retainer'];
        $todo_module_fileds['Rotas'] =  ['RotaEmployee'];
        $todo_module_fileds['Sales'] =  ['Quotes', 'Sales Invoice', 'Sales Order', 'Account', 'Contact', 'Opportunities', 'Case'];
        $todo_module_fileds['Taskly'] =  ['Projects', 'Tasks', 'Bugs'];
        $todo_module_fileds['ChildcareManagement'] =  ['Inquiry','Parent', 'Child'];
        $todo_module_fileds['Holidayz'] =  ['Hotel Customers'];
        $todo_module_fileds['CMMS'] =  ['Component', 'Supplier', 'Purchase Orders'];
        $todo_module_fileds['Fleet'] =  ['Driver', 'Customer', 'Vehicle'];
        $todo_module_fileds['TourTravelManagement'] =  ['Tour'];
        $todo_module_fileds['DoubleEntry'] =  ['Journal Account'];
        $todo_module_fileds['Internalknowledge'] =  ['Book', 'Article'];
        $todo_module_fileds['School'] =  ['Addmission', 'Student', 'Parent', 'Home Work'];
        $todo_module_fileds['MusicInstitute'] =  ['Student', 'Teacher', 'Instrument', 'Class'];
        $todo_module_fileds['GymManagement'] =  ['Trainer', 'Member', 'Measurement'];
        $todo_module_fileds['AgricultureManagement'] =  ['Agriculture Fleet', 'Agriculture Process', 'Agriculture Equipment', 'Agriculture Crop', 'Agriculture User', 'Agriculture Cultivation'];
        $todo_module_fileds['GarageManagement'] =  ['Vehicle', 'Service'];
        $todo_module_fileds['CourierManagement'] =  ['Courier'];
        $todo_module_fileds['Newspaper'] =  ['Agent', 'Journalist', 'News paper', 'Advertisement'];
        $todo_module_fileds['LegalCaseManagement'] =  ['Advocate', 'Courts', 'Cases'];
        $todo_module_fileds['PropertyManagement'] =  ['Units', 'Tenants', 'Maintenance Request'];
        $todo_module_fileds['HospitalManagement'] =  [ 'Doctor', 'Patient', 'Appointment'];
        $todo_module_fileds['BeautySpaManagement'] =  ['Booking'];
        $todo_module_fileds['ParkingManagement'] =  ['Parking'];
        $todo_module_fileds['MachineRepairManagement'] =  ['Machine'];
        $todo_module_fileds['BeverageManagement'] =  ['Raw Material', 'Manufacturing'];
        $todo_module_fileds['SalesAgent'] =  ['Sales Agent'];


        foreach ($todo_module_fileds as $module => $todo_module_filed) {
            foreach ($todo_module_filed as $sm) {
                $check = \Workdo\ToDo\Entities\TodoModuleList::where('module', $module)->where('sub_module', $sm)->first();
                if (!$check) {
                    $new = new \Workdo\ToDo\Entities\TodoModuleList();
                    $new->module = $module;
                    $new->sub_module = $sm;
                    $new->save();
                }
            }
        }
    }
}
