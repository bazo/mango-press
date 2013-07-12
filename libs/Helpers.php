<?php

/**
 * Description of Helpers
 *
 * @author Martin Bažík <martin@bazo.sk>
 */
class Helpers
{

	public static function timeAgoInWords($time)
	{
		if (!$time) {
			return FALSE;
		} elseif (is_numeric($time)) {
			$time = (int) $time;
		} elseif ($time instanceof DateTime) {
			$time = $time->format('U');
		} else {
			$time = strtotime($time);
		}

		$delta = time() - $time;


		$delta = round($delta / 60);
		if ($delta == 0)
			return 'a while ago';
		if ($delta == 1)
			return 'a minute ago';
		if ($delta < 45)
			return "$delta minutes ago";
		if ($delta < 90)
			return 'an hour ago';
		if ($delta < 1440)
			return round($delta / 60) . ' hours ago';
		if ($delta < 2880)
			return 'včera';
		if ($delta < 43200)
			return round($delta / 1440) . 'days ago';
		if ($delta < 86400)
			return 'a month ago';
		if ($delta < 525960)
			return round($delta / 43200) . ' months ago';
		if ($delta < 1051920)
			return 'a year ago';
		return date('F j, Y', $time);
	}


}

