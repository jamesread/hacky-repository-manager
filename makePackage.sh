#!/bin/sh

mkdir -p packages

buildid -n 
VERSION=`buildid -k tag`

DESTDIR=packages/hrm-$VERSION/

mkdir -p $DESTDIR/src

cp src/* $DESTDIR/src

cd packages
zip -r hrm.zip hrm-$VERSION/*
