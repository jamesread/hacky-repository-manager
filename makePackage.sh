#!/bin/sh

rm -rd packages
mkdir -p packages

buildid -n 
buildid -f rpmmacro -W .hrm.rpmmacro
VERSION=`buildid -k tag`

DESTDIR=packages/hrm-$VERSION/

mkdir -p $DESTDIR

cp -r src/ $DESTDIR/
cp -r var/ $DESTDIR/
cp -r README.md $DESTDIR/
cp -r .hrm.rpmmacro $DESTDIR/


cd packages

zip -r hrm.zip hrm-$VERSION/** hrm-$VERSION/.hrm.rpmmacro

