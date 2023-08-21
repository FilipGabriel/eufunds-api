<?php

namespace Modules\Maintenance\Http\Controllers\Admin;

class MaintenanceController
{
    public function phpinfo()
    {
        ob_start();
        phpinfo();
        $content = "";
        if (preg_match('%<style type="text/css">(.*?)</style>.*?<body>(.*)</body>%s', ob_get_clean(), $matches)) {
            $content = "<style>" . preg_replace("/^/m", ".phpinfo ", $matches[1]) . "</style>" .
                "<div class=\"phpinfo\">" . $matches[2] . "</div>";
        }

        return view('maintenance::phpinfo', ['content' => $content]);
    }

    public function logs(string $logFile = null)
    {
        $logsPath = storage_path('logs');

        $logs = glob("{$logsPath}/*.log", GLOB_BRACE);
        rsort($logs);

        if (empty($logFile)) {
            $logFile = basename($logs[0] ?? 'empty');
        }

        abort_if(! file_exists("{$logsPath}/{$logFile}"), 404);
        $logContent = file_get_contents("{$logsPath}/{$logFile}");

        $logsDropdown = [];
        foreach ($logs as $log) {
            $logsDropdown[route('admin.maintenance.logs', ['logFile' => basename($log)])] = basename($log);
        }

        return view('maintenance::logs', [
            'logsDropdown' => $logsDropdown,
            'logContent' => $logContent,
            'logFile' => $logFile,
        ]);
    }
}
