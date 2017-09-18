<?php

//
//	Autodeploy script
//
define('TIME_LIMIT', 9000);
$token = 'ed076287532e86365e841e92bfc50d8c';
$root_path = "/var/www/developers/avtovektor.dev.gns-it.com/dev/";
$web_path = "";
$git_bin = '/usr/bin/git';
$branch = 'refs/heads/develop';
$commiter = 'N/A';
$replybymail = true;

function logger($path, $message)
{
    $logfile = $path . "deploy_log.txt";
    if (file_exists($logfile)) {
        $fh = fopen($logfile, 'a');
    } else {
        $fh = fopen($logfile, 'w');
    }
    fwrite($fh, $message . "\n");
    fclose($fh);
}

// Script body
set_time_limit(TIME_LIMIT);
$content = file_get_contents('php://input');
$json = json_decode($content, true);
$time = time();
$dtime = date("d-m-Y (H:i:s)", $time);
$web_path = $_SERVER['DOCUMENT_ROOT'];

logger($web_path, "Started at " . $dtime . " in " . $web_path);

if (!isset($_GET['token']) || $_GET['token'] !== $token) {
    header('HTTP/1.0 403 Forbidden');
    logger($web_path, "Access Denied" . "\n");
    exit;
} else {
    if ($json['ref'] == $branch) {
        logger($web_path, $content . PHP_EOL);
        if (file_exists($root_path . '.git') && is_dir($root_path)) {
            try {
                chdir($root_path);
                $output = array();
                exec($git_bin . ' pull origin ' . $branch . ' 2>&1', $output, $return_code);

                foreach ($output as $key) {
                    logger($web_path, $key . "\r\n");
                }

                if ($return_code !== 0) {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                    logger($web_path, "500 Internal Server Error on script execution. Check <VirtualHost> config or nginx.service status \r\n");
                } else {
                    $sh_exec = shell_exec("bash deploy.sh 2>&1");
                    logger($web_path, "Executing deploy.sh script...." . $sh_exec . "\r\n");
                    logger($web_path, "*** AUTO PULL SUCCESFUL ***" . "\n");

                    if ($replybymail == true) {
                        $user_name = $json['user_name'];
                        $user_mail = $json['user_email'];
                        $project_name = $json['project']['name'];
                        $subject = 'inf deploy project ' . $project_name;
                        $message = implode("\n", $output);
                        $message .= $sh_exec;
                        if (mail($user_mail, "inf deploy project " . $project_name, $message)) {
                            logger($web_path, "Mail send success!");
                        } else {
                            logger($web_path, "Mail send error!");
                        }
                    }

                }
            } catch (Exception $e) {
                logger($web_path, $e . "\n");
            }
        }
    } else {
        logger($web_path, "Push in: " . $json['ref'] . "\n");
    }
}


?>
