#!/bin/sh

mkdir -p packages

VERSION=`buildid -n`
zip -r packages/hrm-`buildid -k tag`.zip src/ README.md var/
