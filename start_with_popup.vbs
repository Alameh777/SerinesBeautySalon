Set objShell = CreateObject("WScript.Shell")

' Splash screen popup (3 seconds)
objShell.Popup "Starting Beauty Salon App... Please wait.", 3, "Loading", 64

' Run batch hidden
objShell.Run Chr(34) & "C:\xampp\htdocs\BeautySalon\start-app.bat" & Chr(34), 0
