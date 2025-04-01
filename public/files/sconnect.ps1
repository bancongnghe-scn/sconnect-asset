# Luu thông tin vào file
$outputFile = "$env:USERPROFILE\Desktop\system_info.txt"
"System Information Report" > $outputFile
"=========================" >> $outputFile
"" >> $outputFile

# MAINBOARD (Bo mạch chủ)
$mainboard = Get-CimInstance Win32_ComputerSystem
$mainModel = $mainboard.Model

# RAM (Tổng dung lượng)
$ramSize = "{0:N2}" -f ((Get-CimInstance Win32_PhysicalMemory | Measure-Object -Property Capacity -Sum).Sum / 1GB) + " GB"

# Lấy danh sách ổ cứng
$disks = Get-CimInstance Win32_DiskDrive

# Phân loại SSD & HDD dựa trên Model
$ssdList = $disks | Where-Object { $_.Model -match "SSD|NVMe|KINGSTON|Samsung|Crucial|WD Blue SN" } | Select-Object -ExpandProperty Model
$hddList = $disks | Where-Object { $_.Model -notmatch "SSD|NVMe|KINGSTON|Samsung|Crucial|WD Blue SN" } | Select-Object -ExpandProperty Model

# CHIP (CPU)
$cpuModel = (Get-CimInstance Win32_Processor).Name

# GPU (Card đồ họa)
$gpuModel = (Get-CimInstance Win32_VideoController).Name

# Ghi vào file
"MAIN_BOARD: $mainModel" > $outputFile
"RAM: $ramSize" >> $outputFile
"SSD: $ssdList" >> $outputFile
"HDD: $hddList" >> $outputFile
"CHIP: $cpuModel" >> $outputFile
"CARD: $gpuModel" >> $outputFile

Write-Host "Thông tin hệ thống đã được lưu trữ tại: $outputFile"
pause
