
Name: app-apcups
Epoch: 1
Version: 2.0.2
Release: 1%{dist}
Summary: APC Battery Backup Manager
License: GPLv3
Group: ClearOS/Apps
Packager: eGloo
Vendor: eGloo
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base

%description
APC Battery Backup/UPS provides status information, reporting and administrative actions for managing supported APC UPS models.

%package core
Summary: APC Battery Backup Manager - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: apcupsd

%description core
APC Battery Backup/UPS provides status information, reporting and administrative actions for managing supported APC UPS models.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/apcups
cp -r * %{buildroot}/usr/clearos/apps/apcups/

install -D -m 0644 packaging/apcupsd.php %{buildroot}/var/clearos/base/daemon/apcupsd.php

%post
logger -p local6.notice -t installer 'app-apcups - installing'

%post core
logger -p local6.notice -t installer 'app-apcups-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/apcups/deploy/install ] && /usr/clearos/apps/apcups/deploy/install
fi

[ -x /usr/clearos/apps/apcups/deploy/upgrade ] && /usr/clearos/apps/apcups/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-apcups - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-apcups-core - uninstalling'
    [ -x /usr/clearos/apps/apcups/deploy/uninstall ] && /usr/clearos/apps/apcups/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/apcups/controllers
/usr/clearos/apps/apcups/htdocs
/usr/clearos/apps/apcups/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/apcups/packaging
%exclude /usr/clearos/apps/apcups/unify.json
%dir /usr/clearos/apps/apcups
/usr/clearos/apps/apcups/deploy
/usr/clearos/apps/apcups/language
/usr/clearos/apps/apcups/libraries
/var/clearos/base/daemon/apcupsd.php
