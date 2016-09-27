#!/bin/sh

VERSION=`buildid -n`
zip packages/hrm-`buildid -k tag`.zip *.php
