%include SPECS/.hrm.rpmmacro

Name:		hrm
Version:	%{version_formatted_short}
Release:	%{timestamp}.%{?dist}
Summary:	Hacky Repository Manager
BuildArch: noarch

Group:		System/Tools
License:	GPLv2
URL:		http://github.com/jamesread/hacky-repository-manager
Source0:	hrm.zip

BuildRequires:	php
Requires:	php httpd

%description
Hacky Repository Manager

%prep
%setup -q -n hrm-%{tag}


%build

%install
mkdir -p %{buildroot}/usr/share/hrm/
cp -r src/* %{buildroot}/usr/share/hrm/

mkdir -p %{buildroot}/etc/httpd/conf.d/
cp var/hrm.conf %{buildroot}/etc/httpd/conf.d/hrm.conf

%files
/usr/share/hrm/*
/etc/httpd/conf.d/hrm.conf
