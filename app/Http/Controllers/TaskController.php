<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\MiKeyInformation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $key = MiKeyInformation::query();
        $key = $key->where('status', '=', 'pending')->firstOrFail();
        return new TaskResource($key);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $keyInfo = MiKeyInformation::findorfail($id);
        $keyInfo->token = $request->token;
        $keyInfo->server_ip = $request->ip();
        $keyInfo->status = $request->status;
        $keyInfo->save();
        return new TaskResource($keyInfo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}