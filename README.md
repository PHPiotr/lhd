lhd
===

LHD Van Centre (lhdvancentre.com). Symfony 7.4 LTS on PHP 8.3, MariaDB.

Setup: `composer install`, then put the real values in `.env.local`
(`APP_ENV=prod`, `APP_SECRET`, `DATABASE_URL`, `MAILER_TO`, `GOOGLE_MAPS_API_KEY`).

Deploy (production is a Mikrus VPS, server notes in `/root/MCP_documentation.md`):
- App in `/var/www/lhd-app`, php8.3-fpm behind nginx, test copy in `/var/www/lhd-staging`.
- Update: copy the code without `vendor/`, `var/`, `.env.local`, then
  `composer install --no-dev -o` and `runuser -u www-data -- php8.3 bin/console cache:clear`.
- Check: `bash /root/lhd-tools/snapshot.sh http://127.0.0.1 www.lhdvancentre.com` (no 400/500).
- Rollback to the old Symfony 3.2 / PHP 7.2 app: `bash /root/lhd-tools/rollback_prod.sh`.

Deploy traps:
- Customer car photos are not in git. `public/bundles/app/img/stock` must be a symlink
  to the photo directory on the server.
- Do not run `bin/console assets:install`: it deletes unknown folders in `public/bundles/`,
  including `public/bundles/app` (site images and JS).
