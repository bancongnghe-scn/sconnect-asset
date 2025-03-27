# Luu thông tin vào file
$outputFile = "$env:USERPROFILE\Desktop\system_info.txt"
"System Information Report" > $outputFile
"=========================" >> $outputFile
"" >> $outputFile

# Lấy thông tin hệ thống
$hostname = (Get-CimInstance Win32_ComputerSystem).Name
$serverModel = (Get-CimInstance Win32_ComputerSystem).Manufacturer + " " + (Get-CimInstance Win32_ComputerSystem).Model
$osVersion = (Get-CimInstance Win32_OperatingSystem).Caption
$ramSize = "{0:N2}" -f ((Get-CimInstance Win32_PhysicalMemory | Measure-Object -Property Capacity -Sum).Sum / 1GB) + " GB"
$diskModel = (Get-CimInstance Win32_DiskDrive | Select-Object -First 1).Model
$diskSize = "{0:N2}" -f ((Get-CimInstance Win32_DiskDrive | Measure-Object -Property Size -Sum).Sum / 1GB) + " GB"
$cpuModel = (Get-CimInstance Win32_Processor).Name
$cpuCount = (Get-CimInstance Win32_Processor).NumberOfCores
$gpuModel = (Get-CimInstance Win32_VideoController).Name
$gpuAdapterRam = "{0:N2}" -f (((Get-CimInstance Win32_VideoController).AdapterRAM) / 1GB) + " GB"


# Ghi thông tin vào file
"HOSTNAME: $hostname" >> $outputFile
"SERVER_MODEL: $serverModel" >> $outputFile
"OS_VERSION: $osVersion" >> $outputFile
"RAM_SIZE: $ramSize" >> $outputFile
"DISK_MODEL: $diskModel" >> $outputFile
"DISK_SIZE: $diskSize" >> $outputFile

$disname = ""
Get-CimInstance Win32_DiskDrive | ForEach-Object {
    $disks = [PSCustomObject]@{
        Model    = $_.Model
        SizeGB   = "{0:N2}" -f ($_.Size / 1GB) + " GB"
    }
	$disname = $disname + " " + $disks.Model + " (" + $disks.SizeGB + ") |" 
	
}
"DISK: $disname" >> $outputFile
"CPU_MODEL: $cpuModel" >> $outputFile
"CPU_COUNT: $cpuCount" >> $outputFile
"GPU_MODEL: $gpuModel" >> $outputFile
"GPU_ADAPTER_RAM: $gpuAdapterRam" >> $outputFile


Write-Host "Thông tin hệ thống đã được lưu trữ tại: $outputFile"
pause
