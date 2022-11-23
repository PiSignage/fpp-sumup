#!/bin/bash

# fpp-zettle install script
echo "Installing Sumup Plugin for FPP...."

echo "Writing config file...."

file=/home/fpp/media/config/plugin.fpp-sumup.json

defalt_json=$(cat <<EOF
{
	"command": ""
}
EOF
)

if [ -s "$file" ]
then
	echo " Config file exists and is not empty... continuing "
else
	echo " Config file does not exist, or is empty "
   	touch $file
	echo "$defalt_json" > /home/fpp/media/config/plugin.fpp-sumup.json
	sudo chown fpp /home/fpp/media/config/plugin.fpp-sumup.json
fi
