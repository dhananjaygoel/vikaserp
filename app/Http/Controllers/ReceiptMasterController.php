<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View;
use Illuminate\Http\Request;

class ReceiptMasterController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('receipt_master.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}
	/**
	 * Show the form for creating a new Journal Receipt.
	 *
	 * @return Response
	 */
	public function create_journal_receipt()
	{
		return View::make('receipt_master.create_journal');
	}
	/**
	 * Show the form for creating a new Bank Receipt.
	 *
	 * @return Response
	 */
	public function create_bank_receipt()
	{
		return View::make('receipt_master.createb');
	}
	/**
	 * Show the form for creating a new Cash Receipt.
	 *
	 * @return Response
	 */
	public function create_cash_receipt()
	{
		return View::make('receipt_master.createc');
	}
	

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}
	/**
	 * Store a newly created journal receipt resource in storage.
	 *
	 * @return Response
	 */
	public function store_jouranl()
	{
		//
	}
	/**
	 * Store a newly created journal receipt resource in storage.
	 *
	 * @return Response
	 */
	public function store_bank()
	{
		//
	}
	/**
	 * Store a newly created journal receipt resource in storage.
	 *
	 * @return Response
	 */
	public function store_cash()
	{
		//
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
