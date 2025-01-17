#!/usr/bin/env bash
tsfDir="$PREFIX/opt/termsploit"

if [[ -d "$tsfDir" ]]; then
  rm -rf $tsfDir > /dev/null 2>&1
fi
git clone --quiet https://github.com/Anon4You/termsploit-framework $tsfDir
cp $tsfDir/tsfconole $PATH
chmod +x $PATH
echo "termsploit-framework updated..starting tsfconole"

