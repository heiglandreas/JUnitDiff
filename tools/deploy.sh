#!/bin/bash

if [ "$#" != "1" ]; then
    echo "Usage: $0 <version-tag>"
    exit 1;
fi

version="$1"

echo "Fetching Origin"
git fetch origin
echo "Creating 'bumpVersion'-Branch"
git co -b bumpVersion
echo "Replacing version string"
sed -i "" -E "s/setVersion\(.+\)/setVersion('$version')/g" "app/junitdiff"
sed -i "" "s/%version%/$version/g" "app/junitdiff"
echo "Adding App-File"
git add app/junitdiff
echo "Branch Checkin"
git ci -m "Bumped version"
echo "Checking out master-branch"
git co master
echo "Merging upstream changes into master"
git merge origin/master
echo "Merging bumpVersion-Branch into master"
git merge --no-ff --no-edit bumpVersion
echo "Removing 'bumpVersion'-branch"
git br -d bumpVersion
echo "Tagging current master as version $version"
git tag -s $version
echo "Check everything and then run 'git push --tags origin master' "
