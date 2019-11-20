<?php
namespace Application\Foundations;

class DateHelper {
    public static function durationString($then, $now) {
        $dateDiff = $now - $then;
        $measure = "";
        $dateDiff = abs($dateDiff);
        $seconds = $dateDiff;
        $minutes = intval($dateDiff / 60);
        $hours = intval($dateDiff / 3600);
        $days = intval($dateDiff / 86400);
        $years = intval($dateDiff / (365 * 86400));
        if($years > 0) {
            $measure = $years." years";
        } else if($days > 0) {
            $measure = $days." days";
        } else if($hours > 0) {
            $measure = $hours." hours";
        } else if($minutes > 0) {
            $measure = $minutes." minutes";
        } else if($seconds > 0) {
            $measure = $seconds." seconds";
        }
        return $measure;
    }
    public function elapsedString($date) {
        $unixTimestamp = strtotime($date);
        $now = time();
        $dateDiff = $now - $unixTimestamp;
        $measure = "";
        $ago = "";
        if($dateDiff > 0) {
            $ago .= " ago";
        } else if($dateDiff < 0) {
            $ago .= " later";
        } else {
            return "just now";
        }
        $dateDiff = abs($dateDiff);
        $seconds = $dateDiff;
        $minutes = intval($dateDiff / 60);
        $hours = intval($dateDiff / 3600);
        $days = intval($dateDiff / 86400);
        $years = intval($dateDiff / (365 * 86400));
        if($years > 0) {
            $measure = $years." years";
        } else if($days > 0) {
            $measure = $days." days";
        } else if($hours > 0) {
            $measure = $hours." hours";
        } else if($minutes > 0) {
            $measure = $minutes." minutes";
        } else if($seconds > 0) {
            $measure = $seconds." seconds";
        }
        return $measure.$ago;
    }
}
