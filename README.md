# 数据大屏

​        这是一个基于 Ansible 和 PHP 的主机信息仪表盘,用于显示多台主机的 CPU 利用率、内存使用情况和磁盘使用情况。该仪表盘从 Ansible Playbook 收集主机信息,并以图形化的方式在 PHP 应用程序中展示出来。

## 功能特点

1. 使用 Ansible Playbook 收集远程主机的 CPU 型号、CPU 利用率、内存使用情况、磁盘使用情况和网络信息。
2. 将收集到的主机信息保存到 Ansible 控制主机上的 `host_info_*.txt` 文件中。
3. 使用 PHP 读取 `/var/www/html/host_info.txt` 文件中的主机信息,并生成 HTML 和 CSS 构建的响应式用户界面。
4. 每分钟自动执行以下操作:
   - 运行 `ansible-playbook -i hosts.yml system_info.yml` 命令,收集主机信息并生成 `host_info_*.txt` 文件。
   - 等待 10 秒后,删除 `/var/www/html/host_info.txt` 文件。
   - 将 `host_info_*.txt` 文件复制到 `/var/www/html/` 目录。

## 使用方法

1. 确保您的 Ansible 环境已经配置好,并且可以访问远程主机。
2. 将 `system_info.yml` Ansible Playbook 保存到您的 Ansible Playbook 目录中。
3. 创建一个名为 `system_info_cron.sh` 的 Shell 脚本文件,并添加以下内容:

```bash
#!/bin/bash

# 执行 Ansible playbook 收集主机信息
ansible-playbook -i hosts.yml system_info.yml

# 间隔 10 秒后删除 host_info.txt 文件
sleep 10
rm -f /var/www/html/host_info.txt

# 间隔 3 秒后复制 host_info_*.txt 文件到 /var/www/html/ 目录
sleep 3
cp host_info_*.txt /var/www/html/
```

4. 将 `system_info_cron.sh` 脚本文件设置为可执行:

```
chmod +x system_info_cron.sh
```

5. 在 crontab 中添加定时任务:

```
crontab -e
* * * * * /path/to/system_info_cron.sh
```

6. 将 `index.php` 文件保存到 `/var/www/html/` 目录下。
7. 在浏览器中访问 `http://localhost/index.php` 即可查看主机信息仪表盘。

## 代码结构

1. `system_info.yml`: Ansible Playbook,用于收集远程主机的系统信息并保存到 `host_info_*.txt` 文件中。Playbook 输出模板如下:

```
<CPU_MODEL>CPU 型号</CPU_MODEL>
<CPU_UTILIZATION>CPU 利用率</CPU_UTILIZATION>
<DISK_USAGE>磁盘使用情况</DISK_USAGE>
<MEMORY_USAGE>内存使用情况</MEMORY_USAGE>
<NETWORK_INFO>网络信息</NETWORK_INFO>
```

2. `index.php`: PHP 代码,负责从 `/var/www/html/host_info.txt` 文件中读取数据,并生成 HTML 页面。
3. `system_info_cron.sh`: Shell 脚本,用于自动执行 Ansible Playbook 收集主机信息,并将结果文件复制到 `/var/www/html/` 目录。

## 使用截图

![微信截图_20240702162730](D:\Users\微信截图_20240702162730.png)