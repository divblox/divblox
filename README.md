# divblox
divblox Main repo<br>
Release Creation:<br>
The following describes how to create a new divblox release.

Steps:

1. Update the version number within divblox.js to the latest version.
2. Create a new branch from version_x.xx_release in the divblox repo. Branch should be called version_x.xx_release_final
3. Open branch using local IDE and open the divblox setup page. This will generate the environments.php file.
4. Enter the setup page and configure a database and also setup the following dx API key: ubLzFpMv6Go45tWslcB91HhNmXA0PedwryURCYaEInkxQgDOJS8V3iZKT7qjf2
5. Run ionCube project from project file included in this repo "divblox_ionCube_Project.iep"
6. Encoded files are stored in "/divblox - ionCube Encrypted"
7. Select all encoded files and copy into root divblox folder. Override existing files
8. Copy the file "divblox_ioncube_lic" located in this repo to the root folder in the divblox release branch if it is not already in place.
9. Do a quick spot check to see that encoded files are working correctly. Open the component builder and create a new data model crud component called "test". If this is generated correctly, then all should be well. Delete the resulting component folders after testing. 
10. Commit changes. IMPORANT: Remember to exclude environments.php & internal_config.php from the commit.
11. Update the contents of the public divblox repo with the latest branch content
12. Create release from branch on github.com
13. Download release zip file and extract on local machine
14. Remove all github related files (ReadMe.md, .gitignore, etc)
15. Re-zip the file with the following name divblox-x.xx.zip
16. Create a new release on basecamp.divblox.com and upload the zip file there.
17. Update the Divblox Virtual Box VM image with the latest version
18. Update the documentation to reflect the new version number and point to the correct VM image download
- More information on the encoding of Divblox files, using ionCube can be found here: <a href="https://github.com/JohanGriesel/divblox-ionCube-settings">https://github.com/JohanGriesel/divblox-ionCube-settings</a>
<br><br>
