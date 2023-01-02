<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class RequestHelper {

    public static function clientIp($request) {
        if( !empty( $request->server('HTTP_CF_CONNECTING_IP') ) ){
            return $request->server('HTTP_CF_CONNECTING_IP');
        }
        return $request->getClientIp();
    }


    public static function convert_period($type, $time) {
        $period = Carbon::now();
        if ($type == "seconds") {
            return $period->addSeconds($time);
        } else if ($type == "minutes") {
            return $period->addMinutes($time);
        } else if ($type == "hours") {
            return $period->addHours($time);
        } else if ($type == "days") {
            return $period->addDays($time);
        } else if ($type == "weeks") {
            return $period->addWeeks($time);
        } else if ($type == "months") {
            return $period->addMonths($time);
        } else if ($type == "years") {
            return $period->addYears($time);
        }
        else {
            return $period;
        }
    }
}
