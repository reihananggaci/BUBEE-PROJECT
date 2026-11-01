@echo off
title Auto Push ke GitHub - Bubee
echo ==============================================
echo   MENYIMPAN & MENGIRIM PERUBAHAN KE GITHUB
echo ==============================================
echo.

git status --short

echo.
echo Menambahkan perubahan...
git add .

set /p msg="Tulis pesan perubahan (tekan Enter untuk otomatis): "
if "%msg%"=="" set msg=update: perubahan sistem %date% %time%

git commit -m "%msg%"
if %errorlevel% neq 0 (
    echo.
    echo Tidak ada perubahan baru yang perlu dikirim.
) else (
    echo.
    echo Mengirim ke GitHub (git push)...
    git push origin main
    if %errorlevel% equ 0 (
        echo.
        echo ==============================================
        echo   BERHASIL! Perubahan sudah live di GitHub!
        echo ==============================================
    ) else (
        echo.
        echo Gagal push. Pastikan link remote repository sudah dihubungkan.
    )
)

echo.
pause

