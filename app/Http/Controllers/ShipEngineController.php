<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Traits\ShipEngineTrait;

class ShipEngineController extends Controller
{
	
	use ShipEngineTrait;


	public function getEstimateFee()
	{
		return $this->shipEngineRateEstimate();
	}

	public function getShippingFee($data)
	{
		return $this->getLabelFee($data);
	}

}
