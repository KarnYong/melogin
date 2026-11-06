# Prompt: set up the Ubuntu server for the HTTP vs HTTPS workshop

Copy everything inside the box below into Claude Code (or another AI agent) running **on the
Ubuntu server**. Replace the 4 placeholders first:

| Placeholder | Example |
|---|---|
| `<REPO_URL>` | `https://github.com/<user>/melogin.git` |
| `<SERVER_IP>` | `203.0.113.10` |
| `<DOMAIN>` | `melogin.example.com` (its DNS A record must already point to `<SERVER_IP>`) |
| `<EMAIL>` | the email for Let's Encrypt notices |

```
Set up this Ubuntu server to host a small PHP demo page over BOTH plain HTTP and HTTPS,
for a classroom Wireshark workshop that compares the two.

Context
- Source code: <REPO_URL> (contains login.php and logo.png at the repo root; the only
  valid login is admin / 1234 and nothing is stored).
- Public IP: <SERVER_IP>
- Domain (A record already points to this IP): <DOMAIN>
- Email for Let's Encrypt: <EMAIL>

Requirements
1. Install git, nginx, and php-fpm (use the PHP version in Ubuntu's default repos).
2. Clone the repo to /var/www/melogin-src, and make /var/www/html/melogin a symlink to it,
   so the page is at /melogin/login.php. Files readable by www-data. Make sure nginx does
   NOT serve the .git folder (deny access to any path containing /.git).
3. nginx must serve PHP through php-fpm on port 80 for BOTH the bare IP and the domain
   (default_server, server_name _ and <DOMAIN>).
4. Get a Let's Encrypt certificate for <DOMAIN> with certbot's nginx plugin, using
   --no-redirect, so HTTPS on port 443 works for the domain.
5. CRITICAL for the workshop:
   - Do NOT redirect HTTP to HTTPS anywhere (check every server block, including any
     that certbot edits).
   - Do NOT add a Strict-Transport-Security (HSTS) header.
   - Plain HTTP must keep working at http://<SERVER_IP>/melogin/login.php.
6. Open ports 80 and 443 in ufw (allow SSH first), and remind me to also open them in the
   cloud provider's security list or firewall if there is one.
7. Make sure certificate auto-renewal is enabled (certbot timer) and that
   certbot renew --dry-run succeeds.
8. Create /usr/local/bin/melogin-update that runs `git -C /var/www/melogin-src pull --ff-only`
   so I can deploy new commits with one command.

Verify and show me the output of each check
- curl -sI http://<SERVER_IP>/melogin/login.php     -> 200, no Location header, no HSTS
- curl -sI https://<DOMAIN>/melogin/login.php       -> 200, valid certificate, no HSTS
- curl -s -d "username=admin&password=1234" http://<SERVER_IP>/melogin/login.php | grep -i "successful"
- curl -s -d "username=x&password=y"       http://<SERVER_IP>/melogin/login.php | grep -i "failed"
- curl -sI http://<SERVER_IP>/melogin/logo.png      -> 200, image/png
- curl -sI http://<SERVER_IP>/melogin/.git/config   -> 403 or 404
- nginx -t is clean

Finish by printing the two final URLs:
  HTTP : http://<SERVER_IP>/melogin/login.php
  HTTPS: https://<DOMAIN>/melogin/login.php
Ask me before doing anything outside this scope (e.g. changing SSH settings or removing packages).
```

## Later updates

After pushing new commits to GitHub, run on the server: `sudo melogin-update`.
