LIBRARY=$(pwd | xargs basename)
LIBRARY_NAME=$(echo "$LIBRARY" | sed -r "s/-/ /g")

OPEN=$(command -v xdg-open || echo 'open')

SCRUTINIZER_ORGANIZATION=jasny
SCRUTINIZER_GLOBAL_CONFIG=9fc4e5aa-b4a6-4b2b-b698-9a17549e1ddc

echo -n "Repository description: " && read LIBRARY_DESCRIPTION

mv README.md.dist README.md
sed -i "s~{{library}}~$LIBRARY~" README.md
sed -i "s~{{name}}~$LIBRARY_NAME~" README.md
sed -i "s~{{description}}~$LIBRARY_DESCRIPTION~" README.md
sed -i "s~jasny/library~jasny/$LIBRARY~" composer.json
sed -i 's~Jasny\\\\Library~Jasny\\\\'$(echo $LIBRARY | sed -r 's/(^|-)(\w)/\U\2/g')'~' composer.json

mkdir -p src tests
composer install

cp vendor/jasny/php-code-quality/phpunit.xml.dist .
cp vendor/jasny/php-code-quality/phpcs.xml.dist ./phpcs.xml
cp vendor/jasny/php-code-quality/phpstan.neon.dist ./phpstan.neon
cp vendor/jasny/php-code-quality/travis.yml.dist ./.travis.yml
cp vendor/jasny/php-code-quality/bettercodehub.yml.dist ./.bettercodehub.yml

git init
git add .
git commit -a -m "Initial commit"
hub create -d "$LIBRARY_DESCRIPTION"
git push -u origin master

# Travis
travis enable --no-interactive

# Scrutinizer
if [ -n "$SCRUTINIZER_ACCESS_TOKEN" ] ; then
  curl --header "Content-Type: application/json" --request POST --fail --silent --show-error \
    --data "{\"name\":\"jasny/$REPO_NAME\",\"organization\":\"$SCRUTINIZER_ORGANIZATION\",\"global_config\":\"$SCRUTINIZER_GLOBAL_CONFIG\"}" \
    https://scrutinizer-ci.com/api/repositories/g?access_token="$SCRUTINIZER_ACCESS_TOKEN"
else
  echo "Skipping scrutinizer: access token not configured"
fi

# TODO use sensiolabs API or CLI
$OPEN https://insight.sensiolabs.com/projects/new/github

# Better code hub doesn't have an API
$OPEN https://bettercodehub.com/repositories

# Edit README
$OPEN README.md

