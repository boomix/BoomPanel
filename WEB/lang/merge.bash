echo "Start!"
while read p; do
  mkdir -p "${p}/LC_ALL"
  rm -r "${p}/LC_MESSAGES"
  if [ ! -f "${p}/LC_ALL/boompanel.po" ]; then
    cp boompanel.pot "${p}/LC_ALL/boompanel.po"
    sed -i'' -e "s/Language: /Language: ${p}/g" "${p}/LC_ALL/boompanel.po"
  else
    msgmerge --update --backup=off "${p}/LC_ALL/boompanel.po" boompanel.pot
  fi

  msgfmt "${p}/LC_ALL/boompanel.po" -o "${p}/LC_ALL/boompanel.mo"

done < languages
