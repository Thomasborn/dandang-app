<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use Illuminate\Http\Request;
use App\Models\depo;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class DepoController extends Controller
{
    public function index()
    {
        //get all depo
        $depo = depo::all();

        //return collection of depo as a resource
        return new AllResource(true, 'List Data depo', $depo);
    }
     /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);
        
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // Create a new depo
        $depo = Depo::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'user_id' => $request->user_id,
        ]);
        
        if (isset($request->fromAuthController) && $request->fromAuthController) {
            return $depo;
        }
        //return response
        return new AllResource(true, 'Data depo Berhasil Ditambahkan!', $depo);
    }
    public function show($id)
    {
        //find depo by ID
        $depo = depo::find($id);

        //return single depo as a resource
        return new AllResource(true, 'Detail Data depo!', $depo);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $depo
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

        //find depo by ID
        $depo = depo::find($id);

        //check if image is not empty
      
            //update depo without image
            $depo->update([
                'company_name'     => $request->company_name,
                'address'     => $request->address,
                'phone'   => $request->phone,
                'email'   => $request->email,
                'website'   => $request->website,
            ]);
        

        //return response
        return new AllResource(true, 'Data depo Berhasil Diubah!', $depo);
    }

    /**
     * destroy
     *
     * @param  mixed $depo
     * @return void
     */
    public function destroy($id)
    {

        //find depo by ID
        $depo = depo::find($id);

      
        //delete depo
        $depo->delete();

        //return response
        return new AllResource(true, 'Data depo Berhasil Dihapus!', null);
    }
    //
}
