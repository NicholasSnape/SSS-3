@echo off
git add --all
git status
set /p message="Enter commit message: "
git commit -m "%message%"
git push origin master
pause