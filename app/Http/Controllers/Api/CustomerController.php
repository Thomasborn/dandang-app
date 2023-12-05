<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use Illuminate\Http\Request;
use App\Models\customer;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        //get all customer
        $customer = customer::all();

        //return collection of customer as a resource
        return new AllResource(true, 'List Data customer', $customer);
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
            'kode' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:15',
        ]);
        
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // Create a new customer
        $customer = Customer::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nomor_telepon' => $request->nomor_telepon,
        ]);
        
        

        //return response
        return new AllResource(true, 'Data customer Berhasil Ditambahkan!', $customer);
    }
    public function show($id)
    {
        //find customer by ID
        $customer = customer::find($id);

        //return single customer as a resource
        return new AllResource(true, 'Detail Data customer!', $customer);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $customer
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

        //find customer by ID
        $customer = customer::find($id);

        //check if image is not empty
      
            //update customer without image
            $customer->update([
                'company_name'     => $request->company_name,
                'address'     => $request->address,
                'phone'   => $request->phone,
                'email'   => $request->email,
                'website'   => $request->website,
            ]);
        

        //return response
        return new AllResource(true, 'Data customer Berhasil Diubah!', $customer);
    }

    /**
     * destroy
     *
     * @param  mixed $customer
     * @return void
     */
    public function destroy($id)
    {

        //find customer by ID
        $customer = customer::find($id);

      
        //delete customer
        $customer->delete();

        //return response
        return new AllResource(true, 'Data customer Berhasil Dihapus!', null);
    }
    //
}
