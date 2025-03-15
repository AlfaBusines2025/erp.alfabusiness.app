<?php

namespace Workdo\DairyCattleManagement\Http\Controllers;

use Workdo\Account\Entities\Customer;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controller;
use Workdo\DairyCattleManagement\Entities\Animal;
use Workdo\DairyCattleManagement\Entities\AnimalMilk;
use Workdo\DairyCattleManagement\Events\CreateAnimal;
use Workdo\DairyCattleManagement\Events\DestroyAnimal;
use Workdo\DairyCattleManagement\Events\UpdateAnimal;
use Workdo\DairyCattleManagement\DataTables\AnimalDataTable;

class AnimalController extends Controller
{
    public function index(AnimalDataTable $dataTable)
    {
        if (Auth::user()->isAbleTo('animal manage')) {
            return $dataTable->render('dairy-cattle-management::animal.index');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
	{
		if (Auth::user()->isAbleTo('animal create')) {

			 // Listado de clientes de la tabla "customers"
			// KEY = customer_id, VALUE = name
			$customers = Customer::pluck('name', 'customer_id');

			$healthStatusOptions = Animal::$healthstatus;
			$breedingOptions     = Animal::$breedingstatus;

			// <-- AÑADE 'clients' aquí
			return view('dairy-cattle-management::animal.create', compact(
				'healthStatusOptions',
				'breedingOptions',
				'customers'
			));
		} else {
			return redirect()->back()->with('error', __('Permission denied.'));
		}
	}


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('animal create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name'         => 'required|max:120',
                    'species'      => 'required|max:120',
                    'breed'        => 'required|max:120',
                    'birth_date'   => 'required|date',
                    'gender'       => 'required|max:120',
                    'health_status' => 'required',
                    'weight'       => 'required',
                    'breeding'     => 'required',
                    'note'         => 'required|max:255',
                    'image'        => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    // Nuevos campos
                    'nombre_propietario_animal'    => 'nullable|string|max:255',
                    'notas_dieta_animal'           => 'nullable|string|max:255',
                    'numero_pesebrera_animal'      => 'nullable|integer',
                    'instalaciones_pesebrera_animal' => 'nullable|in:VERDES,CAFES,ESCUELITA,PREMIUM,POTREROS,PORTATILES',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            // Store the validated data into the database
            $animal = new Animal();
            $animal->name          = $request->name;
            $animal->species       = $request->species;
            $animal->breed         = $request->breed;
            $animal->birth_date    = $request->birth_date;
            $animal->gender        = $request->gender;
            $animal->health_status = $request->health_status;
            $animal->weight        = $request->weight;
            $animal->breeding      = $request->breeding;
            $animal->note          = $request->note;
            $animal->workspace     = getActiveWorkSpace();
            $animal->created_by    = creatorId();

            // Asignar los nuevos campos
            $animal->nombre_propietario_animal    = $request->nombre_propietario_animal;
            $animal->notas_dieta_animal           = $request->notas_dieta_animal;
            $animal->numero_pesebrera_animal      = $request->numero_pesebrera_animal;
            $animal->instalaciones_pesebrera_animal = $request->instalaciones_pesebrera_animal;

            // Handle file upload if an image is provided
            if (!empty($request->hasfile('image'))) {
                $fileName = time() . "_" . $request->image->getClientOriginalName();
                $upload = upload_file($request, 'image', $fileName, 'animal_image');
                if ($upload['flag'] == 1) {
                    $url = $upload['url'];
                } else {
                    return redirect()->back()->with('error', $upload['msg']);
                }
                $animal->image = $url;
            }
            $animal->save();

            event(new CreateAnimal($request, $animal));

            return redirect()->back()->with('success', __('The animal has been created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $animal = Animal::find($id);


        return view('dairy-cattle-management::animal.show', compact('animal'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('animal edit')) {

            $healthStatusOptions = Animal::$healthstatus;
            $breedingOptions = Animal::$breedingstatus;
            $animal = Animal::find($id);
			
			// Lista de clientes: clave = customer_id, valor = name
        	$customers = Customer::pluck('name', 'customer_id');

            return view('dairy-cattle-management::animal.edit', compact('healthStatusOptions', 'breedingOptions', 'animal','customers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('animal edit')) {
            $animal = Animal::find($id);

            $rules = [
                'name'         => 'required|max:120',
                'species'      => 'required|max:120',
                'breed'        => 'required|max:120',
                'birth_date'   => 'required|date',
                'gender'       => 'required|max:120',
                'health_status' => 'required',
                'weight'       => 'required',
                'breeding'     => 'required',
                'note'         => 'required|max:255',
                // Nuevos campos
                'nombre_propietario_animal'    => 'nullable|string|max:255',
                'notas_dieta_animal'           => 'nullable|string|max:255',
                'numero_pesebrera_animal'      => 'nullable|integer',
                'instalaciones_pesebrera_animal' => 'nullable|in:VERDES,CAFES,ESCUELITA,PREMIUM,POTREROS,PORTATILES',
            ];

            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $animal->name          = $request->name;
            $animal->species       = $request->species;
            $animal->breed         = $request->breed;
            $animal->birth_date    = $request->birth_date;
            $animal->gender        = $request->gender;
            $animal->health_status = $request->health_status;
            $animal->weight        = $request->weight;
            $animal->breeding      = $request->breeding;
            $animal->note          = $request->note;

            // Asignar los nuevos campos
            $animal->nombre_propietario_animal    = $request->nombre_propietario_animal;
            $animal->notas_dieta_animal           = $request->notas_dieta_animal;
            $animal->numero_pesebrera_animal      = $request->numero_pesebrera_animal;
            $animal->instalaciones_pesebrera_animal = $request->instalaciones_pesebrera_animal;

            if ($request->hasFile('image')) {
                // old file delete
                if (!empty($animal->image)) {
                    delete_file($animal->image);
                }
                $name = time() . "_" . $request->image->getClientOriginalName();
                $path = upload_file($request, 'image', $name, 'products');
                $animal->image = empty($path) ? null : $path['url'];
            }

            $animal->save();

            event(new UpdateAnimal($request, $animal));

            return redirect()->back()->with('success', __('The animal has been updated successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('animal delete')) {

            $animal = Animal::find($id);
            event(new DestroyAnimal($animal));
            $animal->delete();
            return redirect()->route('animal.index')->with('success', __('The animal has been deleted successfully.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function storeAnimalMilk(Request $request, $id)
    {
        $milk = $request->milk;

        foreach ($milk as $milkItem) {
            $animalmilk = AnimalMilk::find($milkItem['id']);

            if ($animalmilk == null) {
                $animalmilk = new AnimalMilk();
            }

            $animalmilk->animal_id = $id;
            $animalmilk->date = $milkItem['date'];
            $animalmilk->morning_milk = $milkItem['morning_milk'];
            $animalmilk->evening_milk = $milkItem['evening_milk'];
            $animalmilk->workspace = getActiveWorkSpace();
            $animalmilk->created_by = creatorId();
            $animalmilk->save();
        }
        return redirect()->back()->with('success', __('The animal milk has been created successfully.'));
    }

    public function AnimalMilkDestroy(Request $request)
    {
        $animalmilk = AnimalMilk::where('id', '=', $request->id)->first();
        if (!empty($animalmilk)) {

            $animalmilk->delete();
        }

        return response()->json(['success' => __('The animal milk has been deleted successfully.')]);
    }
}
