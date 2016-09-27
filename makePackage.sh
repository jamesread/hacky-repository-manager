#!/bin/sh

mkdir -p packages

buildid -n 
VERSION=`buildid -k tag`
tar --transform "s,^,/hrm-$VERSION/," -cf packages/hrm.tar src/*
