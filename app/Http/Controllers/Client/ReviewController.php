<?php

namespace App\Http\Controllers\Client;

use DB;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Client\BaseController;

use App\Models\{Product, ClientCurrency, ClientPreference, LoyaltyCard};

class ReviewController extends BaseController
{
    /**
     * Display a listing of the country resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        // echo "review";
        $product = Product::whereHas('reviews')->withCount('reviews')->with('translation_one','media.image');
        // echo "<pre>";
        // print_r($product->toArray());
        // exit();
        // $reviews = [];
        if ($request->ajax()) {
            return Datatables::of($product)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $view_product  =   route('product.edit', $row->id);
                    $btn  = '<div class="form-ul"><div class="inner-div"><a href="'.$view_product.'" class="action-icon editIconBtn"><i class="mdi mdi-square-edit-outline" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->addColumn('product_name', function ($row) {

                    $view_url    =  route('review.show', [$row->sku]);
                    $btn  = '<a href="'.$view_url.'" target="_blank" >'.$row->translation_one->title.'</a>';

                   // $btn  = $row->translation_one->title;
                    return $btn;
                })
                ->rawColumns(['action','product_name'])
                ->make(true);
        }
        return view('backend.review.index');

    }

    /**
     * Show the form for creating a new country resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created country resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified country resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$domain = '',$product_sku)
    {
        $product =  Product::with('translation_one','media.image','vendor','reviews.reviewFiles','reviews.user')->where('sku',$product_sku)->first();
        // echo '<pre>';
        // print_r($product->toArray());
        return view('backend.review.detail',compact('product'));
    }

    /**
     * Show the form for editing the specified country resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
    }

    /**
     * Update the specified country resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        }

    /**
     * Remove the specified country resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id,Request $request)
    {
   }

      /**
     * update the specified country resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeCategoryStatus($category_id,Request $request)
    {

    }


}

