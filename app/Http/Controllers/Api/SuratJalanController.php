<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use App\Models\barang;
use App\Models\barang_surat_jalan;
use Illuminate\Http\Request;
use App\Models\surat_jalan;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class SuratJalanController extends Controller
{
    public function index()
    {
        //get all suratJalan
        $suratJalan = surat_jalan::with(['barangSuratJalan.barang:id,harga,uom,nama'])->get();


        //return collection of suratJalan as a resource
        return new AllResource(true, 'List Data suratJalan', $suratJalan);
    }
     /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
                    // Validate the request for surat_jalan
            $validatorSuratJalan = Validator::make($request->all(), [
                'sales_id' => 'required|exists:sales,id',
                'barang_detail' => 'required|array', // Ensure that barang_details is an array
                'barang_detail.*.barang_id' => 'required|exists:barang,id',
                'barang_detail.*.jumlah_barang' => 'required|integer|min:0',
            ]);

            // Check if validation fails for surat_jalan
            if ($validatorSuratJalan->fails()) {
                return response()->json($validatorSuratJalan->errors(), 422);
            }

            // Create a new surat_jalan
            $suratJalan = surat_jalan::create([
                'sales_id' => $request->sales_id,
                'tanggal' => date('Y-m-d')
            ]);
            
            $barangSuratJalanArray = [];
            
            // Loop through each barang detail and create barang_surat_jalan entries
            foreach ($request->barang_detail as $barangDetail) {
                $barangSuratJalanItem = barang_surat_jalan::create([
                    'surat_jalan_id' => $suratJalan->id,
                    'barang_id' => $barangDetail['barang_id'],
                    'jumlah_barang' => $barangDetail['jumlah_barang'],
                ]);
                $barangData = Barang::find($barangSuratJalanItem->barang_id, ['harga', 'uom', 'nama']);

                if ($barangData) {
                    $barangSuratJalanItem->harga = $barangData->harga;
                    $barangSuratJalanItem->uom = $barangData->uom;
                    $barangSuratJalanItem->nama = $barangData->nama;
                } else {
                    // Handle the case where the barang data is not found
                    return response()->json(['error' => 'Barang id is not found'], 422);

                    
                }
                // Append the $barangSuratJalanItem to the array
                $barangSuratJalanArray[] = $barangSuratJalanItem;
            }
            
            // // Assign the array to the $suratJalan object
            // $suratJalan->barangSuratJalan = $barangSuratJalanArray;
            
            // // Save the $suratJalan object with the associated array of barang_surat_jalan objects
            // $suratJalan->save();
            
            //return response
            return new AllResource(true, 'Data suratJalan Berhasil Diubah!', [
                'suratJalan' => $suratJalan,
                'barang_surat_jalan' => $barangSuratJalanArray,
            ]);
            
        }
                    
     public function show($id)
                {
                    //find suratJalan by ID
                    $suratJalan = surat_jalan::with(['barangSuratJalan.barang:id,harga,uom,nama'])->find($id);

                    // Check if the surat_jalan record is not found
                    if (!$suratJalan) {
                        return response()->json(['error' => 'Surat Jalan not found'], 404);
                    }
                    
                    // Check if the relationship is loaded and has data
                    if (!$suratJalan->relationLoaded('barangSuratJalan') || $suratJalan->barangSuratJalan->isEmpty()) {
                        return response()->json(['error' => 'No related Barang Surat Jalan found'], 404);
                    }
                    
                    // Extract the specific columns from the related data
                    $show = $suratJalan->barangSuratJalan->map(function ($item) {
                        return [
                            'id' => $item['id'],
                            'surat_jalan_id' => $item['surat_jalan_id'],
                            'barang_id' => $item['barang_id'],
                            'jumlah_barang' => $item['jumlah_barang'],
                            'harga' => $item['barang']['harga'],
                            'uom' => $item['barang']['uom'],
                            'nama' => $item['barang']['nama'],
                            'created_at' => $item['created_at'],
                            'updated_at' => $item['updated_at'],
                        ];
                    });
                    
                    // return response()->json(['data' => $show]);
                    
                            
                            // $transformedSuratJalan = [
                            //     'id' => $suratJalan->id,
                            //     'sales_id' => $suratJalan->sales_id,
                            //     'tanggal' => $suratJalan->tanggal,
                            //     'created_at' => $suratJalan->created_at,
                            //     'updated_at' => $suratJalan->updated_at,
                            //     'barang_surat_jalan' => $suratJalan->barang_surat_jalan->map(function ($item) {
                            //         return [
                            //             'id' => $item['id'],
                            //             'surat_jalan_id' => $item['surat_jalan_id'],
                            //             'barang_id' => $item['barang_id'],
                            //             'jumlah_barang' => $item['jumlah_barang'],
                            //             'harga' => $item['barang']['harga'],
                            //             'uom' => $item['barang']['uom'],
                            //             'nama' => $item['barang']['nama'],
                            //             'created_at' => $item['created_at'],
                            //             'updated_at' => $item['updated_at'],
                            //         ];
                            //     }),
                            // ];
                            
                            // return response()->json(['data' => $transformedSuratJalan]);
        //return single suratJalan as a resource
        return new AllResource(true, 'Detail Data suratJalan!', $show);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $suratJalan
     * @return void
     */
    public function update(Request $request, $id)
    {
     // Validate the request for surat_jalan
$validatorSuratJalan = Validator::make($request->all(), [
    'sales_id' => 'required|exists:sales,id',
    'tanggal' => 'required|date',
    'barang_detail' => 'required|array', // Ensure that barang_details is an array
    'barang_detail.*.barang_id' => 'required|exists:barang,id',
    'barang_detail.*.jumlah_barang' => 'required|integer|min:0',
]);

// Check if validation fails for surat_jalan
if ($validatorSuratJalan->fails()) {
    return response()->json($validatorSuratJalan->errors(), 422);
}

// Create a new surat_jalan
$suratJalan = surat_jalan::create([
    'sales_id' => $request->sales_id,
    'tanggal' => $request->tanggal,
]);

// Loop through each barang detail and create barang_surat_jalan entries
foreach ($request->barang_detail as $barangDetail) {
    barang_surat_jalan::create([
        'surat_jalan_id' => $suratJalan->id,
        'barang_id' => $barangDetail['barang_id'],
        'jumlah_barang' => $barangDetail['jumlah_barang'],
    ]);
}


        //return response
        return new AllResource(true, 'Data suratJalan Berhasil Diubah!', [
            'suratJalan' => $suratJalan,
            'barang_surat_jalan' => $suratJalan->barangSuratJalan,
        ]);
    }

    /**
     * destroy
     *
     * @param  mixed $suratJalan
     * @return void
     */
    public function destroy($id)
    {

        //find suratJalan by ID
        $suratJalan = surat_jalan::find($id);

      
        //delete suratJalan
        $suratJalan->delete();

        //return response
        return new AllResource(true, 'Data suratJalan Berhasil Dihapus!', null);
    }
    //
}
