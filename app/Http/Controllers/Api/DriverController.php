<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use Illuminate\Http\Request;
use App\Models\driver;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    public function index()
    {
        //get all driver
        $driver = driver::all();

        //return collection of driver as a resource
        return new AllResource(true, 'List Data driver', $driver);
    }
     /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
           
            // Define validation rules
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            // 'alamat' => 'required|string|max:255',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create a new driver
        $driver = driver::create([
            'nama' => $request->nama,
            'user_id' => $request->user_id,
            // 'alamat' => $request->alamat,
        ]);

        // Associate the driver with a user (assuming 'user_id' is provided in the request)
        // if ($request->has('user_id')) {
        //     $driver->user_id = $request->user_id;
        //     $driver->save();
        // }
        if (isset($request->fromAuthController) && $request->fromAuthController) {
            return $driver;
        }
        //return response
        return new AllResource(true, 'Data driver Berhasil Ditambahkan!', $driver);
    }
    public function show($id)
    {
        //find driver by ID
        $driver = driver::find($id);

        //return single driver as a resource
        return new AllResource(true, 'Detail Data driver!', $driver);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $driver
     * @return void
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'company_name'     => 'required',
            'address'     => 'required',
            'phone'   => 'required',
            'email'   => 'required',
            'website'   => 'required',
           
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find driver by ID
        $driver = driver::find($id);

        //check if image is not empty
      
            //update driver without image
            $driver->update([
                'company_name'     => $request->company_name,
                'address'     => $request->address,
                'phone'   => $request->phone,
                'email'   => $request->email,
                'website'   => $request->website,
            ]);
        

        //return response
        return new AllResource(true, 'Data driver Berhasil Diubah!', $driver);
    }

    /**
     * destroy
     *
     * @param  mixed $driver
     * @return void
     */
    public function destroy($id)
    {

        //find driver by ID
        $driver = driver::find($id);

      
        //delete driver
        $driver->delete();

        //return response
        return new AllResource(true, 'Data driver Berhasil Dihapus!', null);
    }
    //
}
