<?php

namespace App\Http\Controllers\Api;

use App\Models\PenjualanApi;
use App\Models\PenjualanApiDetail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;
//import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class PenjualanApiController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts
        $posts = PenjualanApi::latest()->paginate(5);

        //return collection of posts as a resource
        return new PostResource(true, 'List Data Penjualan', $posts);
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
            'nama_pelanggan'    => 'required',
            'tanggal'           => 'required',
            'jam'               => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Create penjualan
            $penjualan = PenjualanApi::create([
                'nama_pelanggan'    => $request->nama_pelanggan,
                'tanggal'           => $request->tanggal,
                'jam'               => $request->jam,
                'bayar_tunai'       => $request->bayar_tunai,
                'kembali'           => $request->kembali,
                'total'             => $request->total,
            ]);

            // Iterate through items and save them to penjualan_api_details
            $totalSubTotal = 0;
            foreach ($request->items as $item) {
                $totalSubTotal += $item['sub_total'];

                PenjualanApiDetail::create([
                    'penjualan_api_id'  => $penjualan->id,
                    'item'              => $item['item'],
                    'qty'               => $item['qty'],
                    'harga_satuan'      => $item['harga_satuan'],
                    'sub_total'         => $item['sub_total'],
                ]);
            }

            // Check if total sub total matches with total
            if ($totalSubTotal !== $request->total) {
                throw new \Exception('Sub total tidak sama dengan total.');
            }

            // Commit the transaction
            DB::commit();

            // Return success response
            return new PostResource(true, 'Transaksi Berhasil Ditambahkan!', $penjualan);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollBack();

            // Return error response
            return response()->json(['message' => 'Transaksi Gagal Ditambahkan', 'error' => $e->getMessage()], 500);
        }
    }

    public function pajak(Request $request){
        //define validation rules
        $validator = Validator::make($request->all(), [
            'total'         => 'required|numeric',
            'persen_pajak'  => 'required|numeric',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pajak_rp = 0;
        $net_sales = $request->total;
        $persen_pajak = $request->persen_pajak;
        $pajak_rp = $net_sales * ($persen_pajak/100);
        $net_sales_after_tax = $net_sales - $pajak_rp;

        $sum = [
            'net_sales' => $net_sales,
            'pajak_rp'  => $pajak_rp,
            'net_sales_after_tax' => $net_sales_after_tax 
        ];

        try {
            return response()->json($sum, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
