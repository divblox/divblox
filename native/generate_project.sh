#!/bin/bash
command -v npx >/dev/null 2>&1 || { echo >&2 "React Native is not installed. npx not available. Please install react native to continue."; exit 1; }

unameOut="$(uname -s)"
case "${unameOut}" in
    Linux*)     machine="Linux";;
    Darwin*)    machine="MacOS";;
    *)          machine="UNKNOWN:${unameOut}"
esac

echo "Generating react native project from template for ${machine}"

if [ -z "$1" ]
then
  echo "Please specify a name for the app as the 1st argument";
  exit;
fi
if [ -z "$2" ]
then
  echo "Please specify a display name for the app as the 2nd argument";
  exit;
fi
if [ -z "$3" ]
then
  echo "Please specify a server url for the app as the 3rd argument";
  exit;
fi
if [ -z "$4" ]
then
  echo "Please specify a bundle id for the app as the 4th argument";
  exit;
fi

if [ -d "$1" ]
then
  echo "The directory $1 already exists. To export to this directory, first remove it";
  exit;
fi

app_name=$1
display_name=$2
server_url=$3
bundle_id=$4
echo "Inputs:"
echo "App name: $app_name"
echo "App display name: $display_name"
echo "App server url: $server_url"
echo "App bundle id: $bundle_id"

npx react-native init $app_name --template https://github.com/divblox/divblox-react-native-template

replaceAppParameters () {
  # $1 will be the text to search for
  # $2 will be the text to replace with for
  # $3 is the filename (path from native root)
  local search=$1
  local replace=$2
  local file_path=$3

  if [ ${machine} == "MacOS" ]
  then
    sed -i "" "s|${search}|${replace}|g" $file_path
  fi

  if [ ${machine} == "Linux" ]
  then
    sed -i "s|${search}|${replace}|g" $file_path
  fi
}

echo "Updating app parameters..."
if [ ! -d "$app_name" ]
then
  echo "Folder $app_name was not created. Cannot proceed";
  exit;
fi

search='"server_base_url": "https://nativedemo.deploydx.com"'
replace="\"server_base_url\": \"${server_url}\""
file_name="$app_name/app.json"
replaceAppParameters "${search}" "${replace}" "${file_name}"

search='"displayName": "Divblox"'
replace="\"displayName\": \"${display_name}\""
file_name="$app_name/app.json"
replaceAppParameters "${search}" "${replace}" "${file_name}"

search='<string name="app_name">Divblox</string>'
replace="<string name=\"app_name\">${display_name}</string>"
file_name="$app_name/android/app/src/main/res/values/strings.xml"
replaceAppParameters "${search}" "${replace}" "${file_name}"

search='<string>Divblox</string>'
replace="<string>${display_name}</string>"
file_name="$app_name/ios/$app_name/Info.plist"
replaceAppParameters "${search}" "${replace}" "${file_name}"

cd $app_name
echo Export completed.