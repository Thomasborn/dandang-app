<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AllResource;
use App\Models\sales;
use App\Models\barang;
use App\Models\bonus_transaksi;
use App\Models\transaksi;
use App\Models\transaksi_detail;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index()
    {
        //get all transaksis
        $transaksis = transaksi::with('transaksiDetail', 'bonusTransaksi')->get();

        //return collection of transaksis as a resource
        return new AllResource(true, 'List Data transaksis', $transaksis);
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
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // 'driver_id' => 'required|exists:drivers,id',
            'sales_id' => 'required|exists:sales,id',
            // 'total_harga' => 'required|numeric',
            // 'tanggal_transaksi' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'status_transaksi' => 'required|string',
            'tipe_transaksi' => 'required|string',
            // 'ppn' => 'required|numeric',
            // 'gudang_id' => 'required|exists:gudangs,id',
            'customer_id' => 'required|exists:customer,id',
            'details' => 'required|array',
            'details.*.barang_id' => 'required|exists:barang,id',
            'details.*.jumlah_barang' => 'required|numeric',
            'details.*.barang_bonus_id' => 'exists:barang_bonus,id', // Optional if bonuses are provided
            'details.*.jumlah_barang_bonus' => 'numeric', // Optional if bonuses are provided
         
            // Add more validation rules as needed
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }
        $total_harga = 0;
        foreach ($request->details as $detail) {
            $total_harga += $detail['jumlah_barang'] * $detail['harga_barang'];
        }

        // Create a new transaction
        $transaksi = transaksi::create([
            'driver_id' => $request->driver_id,
            'sales_id' => $request->sales_id,
            'total_harga' => $total_harga,
            'tanggal_transaksi' => date('Y-m-d'),
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_transaksi' => $request->status_transaksi,
            'tipe_transaksi' => $request->tipe_transaksi,
            'ppn' => $request->ppn,
            'gudang_id' => $request->gudang_id,
            'customer_id' => $request->customer_id,
            // Add other fields as needed
        ]);
        $transaksiDetailArray=[];
        foreach ($request->details as $detail) {
          $transaksiDetailItem=  transaksi_detail::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $detail['barang_id'],
                'jumlah_barang' => $detail['jumlah_barang'],
                'harga_barang' => $detail['harga_barang'],
                // Add other fields as needed
            ]);
            $transaksiDetailArray[]=$transaksiDetailItem;

        } $bonusTransaksiArray=[];
        if (isset($detail['barang_bonus_id']) && isset($detail['jumlah_barang_bonus'])) {
          $bonusTransaksiItem=  bonus_transaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_bonus_id' => $detail['barang_bonus_id'],
                'jumlah_barang_bonus' => $detail['jumlah_barang_bonus'],
                // Add other fields as needed
            ]);
            $bonusTransaksiArray[]=$bonusTransaksiItem;

        }
        $rincianTransaksi=[
            $transaksi,$transaksiDetailArray,$bonusTransaksiArray

        ];
        
        //return responsetambahka
        return new AllResource(true, 'Data transaksi Berhasil Din!',$rincianTransaksi);
    
    }
    public function show($id)
    {
        //find transaksis by ID
        $transaksis = transaksi::find($id);

        //return single transaksi as a resource
        return new AllResource(true, 'Detail Data transaksis!', $transaksis);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $transaksis
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

        //find transaksis by ID
        $transaksis = transaksi::find($id);

        //check if image is not empty
      
            //update transaksis without image
            $transaksis->update([
                'company_name'     => $request->company_name,
                'address'     => $request->address,
                'phone'   => $request->phone,
                'email'   => $request->email,
                'website'   => $request->website,
            ]);
        

        //return response
        return new AllResource(true, 'Data transaksis Berhasil Diubah!', $transaksis);
    }

    /**
     * destroy
     *
     * @param  mixed $transaksis
     * @return void
     */
    public function destroy($id)
    {

        //find transaksis by ID
        $transaksis = transaksi::find($id);

      
        //delete transaksis
        $transaksis->delete();

        //return response
        return new AllResource(true, 'Data transaksis Berhasil Dihapus!', null);
    }
    //
}
