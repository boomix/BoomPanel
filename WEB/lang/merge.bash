echo "Start!"
while read p; do
  mkdir -p "${p}/LC_MESSAGES"
  if [ ! -f "${p}/LC_MESSAGES/boompanel.po" ]; then
    cp boompanel.pot "${p}/LC_MESSAGES/boompanel.po"
    sed -i'' -e "s/Language: /Language: ${p}/g" "${p}/LC_MESSAGES/boompanel.po"
  else
    msgmerge --update --backup=off "${p}/LC_MESSAGES/boompanel.po" boompanel.pot
  fi

  msgfmt "${p}/LC_MESSAGES/boompanel.po" -o "${p}/LC_MESSAGES/boompanel.mo"

done < languages
