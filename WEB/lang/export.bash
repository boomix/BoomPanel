echo "Start!"
DIR=`perl -e 'use Cwd "abs_path";use File::Basename;print dirname(abs_path(shift))' $0`
echo $DIR
LANG=(`curl -X POST https://api.poeditor.com/v2/languages/list \
           -d api_token="28569c58e5865d530ed1f410c113a96f" \
           -d id="151217" | python -c "import sys, json; inp = json.load(sys.stdin)['result']['languages']; print(' '.join([i['code'] for i in inp]));"`)

for l in "${LANG[@]}"
do
  curl -o "${DIR}/${l}/LC_ALL/boompanel.po" `curl -X POST https://api.poeditor.com/v2/projects/export \
                                           -d api_token="28569c58e5865d530ed1f410c113a96f" \
                                           -d id="151217" \
                                           -d language=$l \
                                           -d type="po" | python -c "import sys, json; print(json.load(sys.stdin)['result']['url']);"`

  curl -o "${DIR}/${l}/LC_ALL/boompanel.mo" `curl -X POST https://api.poeditor.com/v2/projects/export \
                                           -d api_token="28569c58e5865d530ed1f410c113a96f" \
                                           -d id="151217" \
                                           -d language=$l \
                                           -d type="mo" | python -c "import sys, json; print(json.load(sys.stdin)['result']['url']);"`
done

find "${DIR}/.." -iname "*.php" | xargs xgettext --from-code=UTF-8 --default-domain=boompanel -o "${DIR}/boompanel.pot"
sed -i'' -e "s/Content-Type: text/plain; charset=CHARSET/Content-Type: text/plain; charset=UTF-8/g" "${DIR}/boompanel.pot"

while read p; do
  mkdir -p "${p}/LC_ALL"
  if [ ! -f "${p}/LC_ALL/boompanel.po" ]; then
    cp boompanel.pot "${p}/LC_ALL/boompanel.po"
    sed -i'' -e "s/Language: /Language: ${p}/g" "${p}/LC_ALL/boompanel.po"
  else
    msgmerge --update --backup=off "${p}/LC_ALL/boompanel.po" boompanel.pot
  fi

  msgfmt "${p}/LC_ALL/boompanel.po" -o "${p}/LC_ALL/boompanel.mo"

done < languages

curl -X POST https://api.poeditor.com/v2/projects/upload \
     -F api_token="28569c58e5865d530ed1f410c113a96f" \
     -F id="151217" \
     -F updating="terms" \
     -F file=@"${DIR}/boompanel.pot"

