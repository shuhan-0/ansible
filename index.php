<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="20">
    <title>基于ansible的数据小屏</title>
    <style>
        body{font-family:Arial,sans-serif;margin:0;padding:0}.container{max-width:1200px;margin:0 auto;padding:20px}.host-info{display:flex;margin-bottom:40px}.host-info-left,.host-info-right{width:50%;padding:20px;box-sizing:border-box}.host-info h2{margin-top:0;font-size:24px}.host-status-icon{width:20px;height:20px;border-radius:50%;display:inline-block;margin-right:10px}.host-status-icon i{color:#fff;font-size:12px;line-height:20px;text-align:center;width:100%}.gauge{width:100%;height:20px;background-color:#f2f2f2;border-radius:10px;overflow:hidden;margin-top:5px;position:relative;display:flex;align-items:center}.gauge-fill{height:100%;background-color:#4CAF50;border-radius:10px}.gauge-text{position:absolute;right:10px;font-size:14px;font-weight:bold;color:#333}.gauge-label{font-size:14px;font-weight:bold;color:#333;margin-bottom:5px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>数据大屏S Pro MAX +</h1>
        <?php
        $hosts=[];$currentHost=null;foreach(file('/var/www/html/host_info.txt',FILE_IGNORE_NEW_LINES)as$line){if(strpos($line,'machine')===0){if($currentHost!==null)$hosts[]=$currentHost;$currentHost=['name'=>substr($line,0,-1),'cpu_model'=>'','cpu_utilization'=>'','disk_usage'=>'','memory_usage'=>'','network_info'=>''];}else{if(strpos($line,'<CPU_MODEL>')!==false)$currentHost['cpu_model']=getXMLTag($line,'CPU_MODEL');elseif(strpos($line,'<CPU_UTILIZATION>')!==false)$currentHost['cpu_utilization']=getXMLTag($line,'CPU_UTILIZATION');elseif(strpos($line,'<DISK_USAGE>')!==false)$currentHost['disk_usage']=getXMLTag($line,'DISK_USAGE');elseif(strpos($line,'<MEMORY_USAGE>')!==false)$currentHost['memory_usage']=getXMLTag($line,'MEMORY_USAGE');elseif(strpos($line,'<NETWORK_INFO>')!==false)$currentHost['network_info']=getXMLTag($line,'NETWORK_INFO');}}if($currentHost!==null)$hosts[]=$currentHost;foreach($hosts as$host){$status=strpos($host['name'],'主机已开机')!==false?'#4CAF50':'#4CAF50';?>
            <div class="host-info">
                <div class="host-info-left">
                    <h2><div class="host-status-icon" style="background-color:<?php echo $status;?>;"><i class="fas fa-power-off"></i></div><?php echo $host['name'];?></h2>
                    <p><strong style="font-size:18px;"><?php echo $host['cpu_model'];?></strong></p>
                    <p><strong>IPv4地址:</strong> <?php echo $host['network_info'];?></p>
                    <p><strong>内存使用:</strong> <?php echo $host['memory_usage'];?></p>
                    <p><strong>磁盘使用:</strong> <?php echo $host['disk_usage'];?></p>
                </div>
                <div class="host-info-right">
                    <div class="gauge-label">CPU使用率</div>
                    <div class="gauge"><div class="gauge-fill" style="width:<?php echo getCPUUtilization($host['cpu_utilization']);?>%;"></div><span class="gauge-text"><?php echo getCPUUtilization($host['cpu_utilization']);?>%</span></div>
                    <div class="gauge-label">内存使用率</div>
                    <div class="gauge"><div class="gauge-fill" style="width:<?php echo getMemoryUtilization($host['memory_usage']);?>%;"></div><span class="gauge-text"><?php echo getMemoryUtilization($host['memory_usage']);?>%</span></div>
                    <div class="gauge-label">磁盘使用率</div>
                    <div class="gauge"><div class="gauge-fill" style="width:<?php echo getDiskUtilization($host['disk_usage']);?>%;"></div><span class="gauge-text"><?php echo getDiskUtilization($host['disk_usage']);?>%</span></div>
                </div>
            </div>
            <?php }function getXMLTag($line,$tag){$xml="<{$tag}>{$tag}</{$tag}>";$start=strpos($line,$xml)+strlen($tag)+2;$end=strpos($line,"</{$tag}>");return substr($line,$start,$end-$start);}function getCPUUtilization($cpuUtilization){return trim(substr($cpuUtilization,0,-1));}function getMemoryUtilization($memoryUsage){$memoryUsed=(int)substr($memoryUsage,0,strpos($memoryUsage,'MB'));$memoryTotal=(int)substr($memoryUsage,strpos($memoryUsage,'/')+1,strpos($memoryUsage,'MB')-strpos($memoryUsage,'/')-1);return round($memoryUsed/$memoryTotal*100);}function getDiskUtilization($diskUsage){$diskUsed=(int)substr($diskUsage,0,strpos($diskUsage,'/'));$diskTotal=(int)substr($diskUsage,strpos($diskUsage,'/')+1,strpos($diskUsage,'MB')-strpos($diskUsage,'/')-1);return round($diskUsed/$diskTotal*100);}?>
    </div>
</body>
</html>
