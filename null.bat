@echo off
 setlocal enabledelayedexpansion
del /q c:\empty_dir
del /q c:\directory.txt
REM 下面的代码加上sort /r ，表示逆序排列文件夹。子文件夹在前，父文件夹在后。
dir /a:d /b /s "."  | sort /r > "%cd%\directory.txt"
for /f "usebackq delims=" %%i in ("directory.txt") do (
    rem echo "enter dir is: %%i"
    rem cd "%%i"
    rem echo "the bat path is: %~f0" rem print this batfile's whole path.
    echo "cur dir is: "%%i""
    REM 将当前目录下的所有文件打印到以下文件中：
    dir /a /b "%%i" >"c:\folder_content.txt"
    rem echo "======================================================="
    rem echo "==================fold content: begin ================="
    :type "c:\folder_content.txt"
    rem echo "==================fold content: end   ================="
    rem echo "======================================================="
    
    REM 下面的findstr命令查找当前文件是否有内容，如果查找成功表示目录不是空的，否则是空的。
    REM 注意下面的两个 '与' 和 两个 '或' 符号。    
    findstr . "c:\folder_content.txt" >nul 2>nul && ( echo "The folder "%%i" is NOT NOT NOT empty") || ( echo "The folder "%%i" is empty" && echo "%%i">>"%cd%\empty_dir" && rd "%%i")
    del "c:\folder_content.txt"
    )
 del  "%cd%\directory.txt"