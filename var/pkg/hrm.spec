Name:		hrm
Version:	%{tag}
Release:	1%{?dist}
Summary:	Hacky Repository Manager

Group:		System/Tools
License:	GPLv2
URL:		http://github.com/jamesread/hacky-repository-manager
Source0:	hrm.zip

BuildRequires:	
Requires:	php

%description
Hacky Repository Manager

%prep
%setup -q -n hrm-%{tag}.zip


%build

%install
mkdir -p %{buildroot}/usr/share/hrm/
cp src/*.php %{buildroot}/usr/share/hrm/

%files
/usr/share/hrm/*

%changelog
* Initial Version