<?php

namespace App\Http\Controllers;

use App\Helpers\RequestHelper;
use App\Http\Resources\TaskResource;
use App\Models\MiKeyInformation;
use App\Models\MiServer;
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
        $server = MiServer::where('auth_uid', '=', $id)->firstOrFail();
        $server->status = 'online';
        $server->save();

        $key = MiKeyInformation::query();
        $key = $key->where([['status', '=', 'pending'], ['server_id', '=', $server->id]])->firstOrFail();
        return new TaskResource($key);
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
        $keyInfo->server_ip = RequestHelper::clientIp($request);
        $keyInfo->status = $request->status;
        if($request->status == 'success') {
            $server = $keyInfo->server_info;
            RequestHelper::update_mi_server($server->id, 1, $server->interval, $server->interval_type);
        }
        $keyInfo->save();
        return new TaskResource($keyInfo);
    }

    public function update_servers() {
        $servers = MiServer::query()->update(['status' => 'offline']);
        return response()->json([
            'message' => 'Servers status are updated',
        ]);
    }

    public function update_servers_cron() {
        $server = MiServer::query()->update([['status' => 'offline'], ['count' => '0']]);
        return response()->json([
            'message' => 'Server count is successfully updated'
        ]);
    }

    public function update_server($id) {
        $serverInfo = MiServer::findorfail($id);
        $serverInfo->status = 'online';
        $serverInfo->save();
        return response()->json([
            'Server Status' => $serverInfo->status,
            'Server Name' => $serverInfo->name,
            'Server Ip' => $serverInfo->ip
        ]);
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
