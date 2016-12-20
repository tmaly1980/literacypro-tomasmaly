<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Band;
use Illuminate\Http\Request;

class BandController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$bands = Band::sortable(['id'=>'desc'])->paginate(10);

		return view('bands.index', compact('bands'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('bands.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$band = new Band();

		$band->name = $request->input("name");
        $band->start_date = $request->input("start_date");
        $band->website = $request->input("website");
        $band->still_active = $request->input("still_active");

		$band->save();

		flash('Band created successfully');

		return redirect()->route('bands.show',[$band->id]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$band = Band::findOrFail($id);

		return view('bands.show', compact('band'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$band = Band::findOrFail($id);

		return view('bands.edit', compact('band'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$band = Band::findOrFail($id);

		$band->name = $request->input("name");
        $band->start_date = $request->input("start_date");
        $band->website = $request->input("website");
        $band->still_active = $request->input("still_active");

		$band->save();
		flash('Band updated successfully');

		return redirect()->route('bands.show',[$band->id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$band = Band::findOrFail($id);
		$band->delete();

		flash('Band deleted successfully');
		return redirect()->route('bands.index');
	}

}
