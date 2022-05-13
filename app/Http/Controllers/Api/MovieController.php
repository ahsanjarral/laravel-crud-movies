<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Http\Resources\MovieResource;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     * this controller function returns all records
     * from the storage and no authentication used 
     * hence this is not a realtime production product
     * however do demonstrated CRUD this app alows to 
     * create, retrieve, update and delete a records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MovieResource::collection(Movie::all());
    }

    /**
     * Store a newly created resource in storage.
     * this is not suitable for production if the 
     * security is for utmost concern or some 
     * sensitive data is being transmitted.
     * due to time constraints no use authentication
     * is completed and also there is no implementation 
     * of request confirmation if its form the right source
     * or where we expect it from.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $movie = Movie::create($this->validateRequest($request));
        return new MovieResource($movie);
    }

    /**
     * Display the specified resource.
     * this method is retriving data from database
     * no validation or user authentication done 
     * purely due to time constraint and demo purpose
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        return new MovieResource($movie);
    }

    /**
     * Update the specified resource in storage.
     * this method is using storage to save data
     * give more time in hand i would validate user
     * validate the request is form correct source 
     * using authenticate token and allow update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        $movie->update($this->validateRequest($request));
        return new MovieResource($movie);
    }

    /**
     * Remove the specified resource from storage.
     * no validation of user whatsoever due to time contraint
     * if given more time we can validate user with token and 
     * if successfull then only allow to delete record. 
     * this is for demonstration purpose.
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();
        return response()->noContent();
    }

    /**
     * validate user input data 
     * given more time these can 
     * be restricted and sanitised
     */
    protected function validateRequest($request){
        return $request->validate([
            'title' => 'required|max:150',
            'category' => ['required'],
            'release_date' => ['required'],
            'desc' => ['nullable'],
            'rating' => ['nullable'],
        ]);
    }
}
