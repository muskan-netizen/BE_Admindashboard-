<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DeliverySlot;
use Illuminate\Http\Request;
use DataTables;

class DeliverySlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $deliverySlot = DeliverySlot::all();
        if ($request->ajax()) {
            return Datatables::of($deliverySlot)
                ->addIndexColumn()
                ->addColumn('title', function ($deliverySlot) {
                    return $deliverySlot->title;
                })
                ->addColumn('start_time', function ($deliverySlot) {
                    return $deliverySlot->start_time;
                })
                ->addColumn('end_time', function ($deliverySlot) {
                    return $deliverySlot->end_time;
                })
                ->addColumn('price', function ($deliverySlot) {
                    return $deliverySlot->price;
                })
                ->addColumn('action', function ($deliverySlot) use ($request) {
                    $delete_url = route('delivery-slot.destroy', $deliverySlot->id);
                    $action = '<div class="form-ul" style="width: 60px;">
                    <div class="inner-div" style="float: left;">
                        <a class="action-icon addSlotBtn"
                            href="javascript:void(0);"
                            data-id="'.$deliverySlot->id.'" data-title="'.$deliverySlot->title.'" data-start-time="'.$deliverySlot->start_time.'" data-end-time="'.$deliverySlot->end_time.'" data-price="'.$deliverySlot->price.'"><i
                                class="mdi mdi-square-edit-outline"></i></a>
                    </div>
                    <div class="inner-div">
                        <form id="deleteproduct_'.$deliverySlot->id.'" method="POST"
                            action="'. $delete_url.'">
                            <input type="hidden" name="_token" value="' . csrf_token() . '" />
                            <input type="hidden" name="_method" value="DELETE">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary-outline action-icon delete-product" data-destroy_url="'. $delete_url.'" data-rel="'.$deliverySlot->id.'"><i class="mdi mdi-delete"></i></button>
                            </div>
                        </form>
                    </div>
                </div>';
                return $action;
                })
                ->addColumn('status', function ($deliverySlot) {
                    if($deliverySlot->status == 1){
                        $status = 'Disabled';
                    }else{
                        $status = 'Active';
                    }
                    return $status;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('backend.delivery-slots.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'title' => $request->slot_title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'price' => $request->price
        ];
        $deliverySlot = DeliverySlot::updateOrCreate([ 'id'   => $request->slot_id, ], $data);
        if($request->slot_id != ''){
            return redirect()->back()->with('success', 'Slot updated successfully');
        }
        return redirect()->back()->with('success', 'Slot added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliverySlot  $deliverySlot
     * @return \Illuminate\Http\Response
     */
    public function show(DeliverySlot $deliverySlot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliverySlot  $deliverySlot
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliverySlot $deliverySlot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliverySlot  $deliverySlot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliverySlot $deliverySlot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliverySlot  $deliverySlot
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliverySlot $deliverySlots, $domain = '', $id)
    {
        $deliverySlots = DeliverySlot::find($id);
        $deliverySlots->delete();
        return redirect()->back()->with('success', 'Slot deleted successfully');
    }
}
