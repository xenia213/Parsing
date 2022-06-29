<?php

if ($argc > 2) {
	exit("Specify the path to the access_log\n file or run the script.sh\n executable file");
} else if ($argc == 1) {
	exit("Specify the path to the access_log\n file or run the script.sh\n executable file");
}

define("ACCESS_LOG_PATH", $argv[1]);

$result = [
    'views'    => 0,
    'urls'     => 0,
    'traffic'  => 0,
    'crawlers' => [
        'Google' => 0,
        'Bing'   => 0,
        'Baidu'  => 0,
        'Yandex' => 0,
    ],
    'statusCodes' => [],
];

$unique_urls = [];
$status_codes = [];
$bytes_status = "200";
$pattern = '/^([^ ]+) ([^ ]+) ([^ ]+) (\[[^\]]+\]) "(.*) (.*) (.*)" ([0-9\-]+) ([0-9\-]+) "(.*)" "(.*)"$/';

if ($open_file = fopen(ACCESS_LOG_PATH, 'r') or die("Error opening the \"{ACCESS_LOG_PATH}\" file\n")) {
    $i = 1;
    while (!feof($open_file)) {
        if ($row = trim(fgets($open_file))) {
            if (preg_match($pattern, $row, $matches)) {
                list(
                    $row,
                    $remote_urls,
                    $logname,
                    $user,
                    $time,
                    $method,
                    $request,
                    $protocol,
                    $status,
                    $bytes,
                    $referer,
                    $user_agent
                ) = $matches;

                $result['views'] = $i;

                if (!array_search($remote_urls, $unique_urls)) {
                    $unique_urls[] = $remote_urls;
                }
                $result['urls'] = count($unique_urls);

                if (!array_key_exists($status, $status_codes)) {
                    $status_codes[$status] = 1;
                } else {
                    $status_codes[$status]++;
                }
                $result['status_codes'] = $status_codes;

                if ($status === $bytes_status) {
                    $bytes_stat[] = $bytes; 
                }
                $result['traffic'] = array_sum($bytes_stat); 
                 
                $bots_pattern = "/bot|google|baidu|bing|msn|teoma|slurp|yandex/i";
                preg_match($bots_pattern, $user_agent, $bot_result);
                if (!empty($bot_result)) {
                    list($bot_name) = $bot_result;
                    if (!array_key_exists($bot_name, $result['crawlers'])) {
                        $result['crawlers'][$bot_name] = 1;
                    } else {
                        $result['crawlers'][$bot_name]++;
                    }
                }
            } else {
                error_log("It is not possible to parse the string $i: $row");
            }
        }
        $i++;
    }
}

echo "The result of the access_log file data:\n".json_encode($result, JSON_PRETTY_PRINT);

