%include SPECS/.hrm.rpmmacro

Name:		hrm
Version:	%{version_formatted_short}
Release:	1%{?dist}
Summary:	Hacky Repository Manager
Arch: noarch
BuildArch: noarch

Group:		System/Tools
License:	GPLv2
URL:		http://github.com/jamesread/hacky-repository-manager
Source0:	hrm.zip

BuildRequires:	php
Requires:	php

%description
Hacky Repository Manager

%prep
%setup -q -n hrm-%{tag}


%build

%install
mkdir -p %{buildroot}/usr/share/hrm/
cp src/*.php %{buildroot}/usr/share/hrm/

%files
/usr/share/hrm/*
