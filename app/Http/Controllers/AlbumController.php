<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Album;
use App\Band;
use Illuminate\Http\Request;

class AlbumController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$band_id = request('band_id');
		$cond = !empty($band_id) ? ['band_id'=>$band_id] : [];
		$albums = Album::where($cond)->sortable(['id'=>'desc'])->paginate(10);

		return view('albums.index', compact('albums'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('albums.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$album = new Album();

		$album->band_id = $request->input("band_id");
        $album->name = $request->input("name");
        $album->recorded_date = $request->input("recorded_date");
        $album->release_date = $request->input("release_date");
        $album->number_of_tracks = $request->input("number_of_tracks");
        $album->label = $request->input("label");
        $album->producer = $request->input("producer");
        $album->genre = $request->input("genre");

		$album->save();

		flash('Album created successfully');
		return redirect()->route('albums.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$album = Album::findOrFail($id);

		return view('albums.show', compact('album'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$album = Album::findOrFail($id);

		return view('albums.edit', compact('album'));
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
		$album = Album::findOrFail($id);

		$album->band_id = $request->input("band_id");
        $album->name = $request->input("name");
        $album->recorded_date = $request->input("recorded_date");
        $album->release_date = $request->input("release_date");
        $album->number_of_tracks = $request->input("number_of_tracks");
        $album->label = $request->input("label");
        $album->producer = $request->input("producer");
        $album->genre = $request->input("genre");

		$album->save();
		flash('Album updated successfully');

		return redirect()->route('albums.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$album = Album::findOrFail($id);
		$album->delete();
		flash('Album deleted successfully.');

		return redirect()->route('albums.index');
	}

}
