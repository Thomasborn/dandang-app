<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use Illuminate\Http\Request;
use App\Models\barang;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        //get all daftar_barang
        $daftar_barang = barang::all();

        //return collection of daftar_barang as a resource
        return new AllResource(true, 'List Data daftar_barang', $daftar_barang);
    }
     /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'  => 'required',
            'harga' => 'required|numeric|min:0',
            'stok'  => 'required|integer|min:0',
            'uom'   => 'required',
            'tipe'  => 'required',
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Assuming you allow only image gambars (jpeg, png, jpg, gif) with a maximum size of 2048 KB.
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        // Assuming you have an 'upload' folder in your public directory for storing images.
        $fileName = uniqid() . '_' . $request->gambar->getClientOriginalName();
        $gambarPath =$request->gambar->storeAs('public/barang', $fileName);
          
        $daftar_barang = barang::create([
            'nama'   => $request->nama,
            'harga'  => $request->harga,
            'stok'   => $request->stok,
            'uom'    => $request->uom,
            'tipe'   => $request->tipe,
            'gambar' => $gambarPath, // Store the image path in the 'gambar' column.
        ]);
        
        // $validator = Validator::make($request->all(), [
        //     'nama'   => 'required',
        //     'harga'  => 'required|numeric|min:0',
        //     'stok'   => 'required|integer|min:0',
        //     'uom'    => 'required',
        //     'tipe'   => 'required|exists:tipe,id',
        //     'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'ukuran' => 'required',
        // ]);
    
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }
    
        // // Menggunakan id tipe yang divalidasi di atas untuk menyimpan data barang.
        // $barang = Barang::create([
        //     'nama'       => $request->nama,
        //     'tipe'       => $request->tipe,
        //     'gambar'     => $request->file('gambar')->store('public/barang'),
        //     'deskripsi'  => $request->deskripsi,
        // ]);
    
        // // Menyimpan data kemasan.
        // $kemasan = Kemasan::create([
        //     'ukuran' => $request->ukuran,
        //     'uom'    => $request->uom,
        //     'harga'  => $request->harga,
        // ]);
    
        // // Menyimpan relasi barang_kemasan.
        // $barang_kemasan = BarangKemasan::create([
        //     'barang_id'  => $barang->id,
        //     'kemasan_id' => $kemasan->id,
        //     'stok'       => $request->stok,
        // ]);
    

        return new AllResource(true, 'Data barang Berhasil Ditambahkan!', $daftar_barang);
         }
    public function show($id)
    {
        //find daftar_barang by ID
        $daftar_barang = barang::find($id);

        //return single barang as a resource
        return new AllResource(true, 'Detail Data daftar_barang!', $daftar_barang);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $daftar_barang
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

        //find daftar_barang by ID
        $daftar_barang = barang::find($id);

        //check if image is not empty
      
            //update daftar_barang without image
            $daftar_barang->update([
                'company_name'     => $request->company_name,
                'address'     => $request->address,
                'phone'   => $request->phone,
                'email'   => $request->email,
                'website'   => $request->website,
            ]);
        

        //return response
        return new AllResource(true, 'Data daftar_barang Berhasil Diubah!', $daftar_barang);
    }

    /**
     * destroy
     *
     * @param  mixed $daftar_barang
     * @return void
     */
    public function destroy($id)
    {

        //find daftar_barang by ID
        $daftar_barang = barang::find($id);

      
        //delete daftar_barang
        $daftar_barang->delete();

        //return response
        return new AllResource(true, 'Data daftar_barang Berhasil Dihapus!', null);
    }
    //
}
