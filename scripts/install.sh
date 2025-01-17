#!/usr/bin/env bash

tsfDir="$PREFIX/opt/termsploit"
c1="\e[31m"
c2="\e[32m"
c0="\e[0m"

printf "$c2 --Updating repositoris...$c0\n"
apt update -y

printf "$c2 --Installing required packages...$c0\n"
apt install git curl python build-essential cmake ninja libopenblas libandroid-execinfo patchelf binutils-is-llvm rust jq python-pillow nodejs qpdf libiconv libxml2 libxslt -y

apt cleane

git clone --quiet https://github.com/Anon4You/termsploit-framework $tsfDir

cp $tsfDir/tsfconsole $PATH/tsfconsole
chmod +x $PATH/tsfconsole

printf "$c2 --Installing pip dependencies...\n$c1!! It may take some time be patient...$c0\n"
pip install setuptools wheel packaging pyproject_metadata cython meson-python versioneer requests requests[sock] tqdm aiohttp bcrypt pikepdf

printf "$c2 --Installing npm dependencies..."
npm -g install bash-obfuscate

printf "$c2 termsploit-framework installed..\nLaunching $c1tsfconsole...$c0\n";sleep 2

tsfconsole
