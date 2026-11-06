# Me Login: HTTP vs HTTPS workshop site

**Server setup:** see `SERVER_SETUP_PROMPT.md` (a ready-to-paste prompt for an AI agent on the
Ubuntu server: clone from GitHub, nginx + PHP, HTTP and HTTPS, no redirect/HSTS, checks).

Files: `login.php` + `logo.png`. Upload both to a folder named `melogin/` on any PHP host,
so the URLs stay `.../melogin/login.php` as on the slides.

## One Ubuntu VPS, serving both HTTP and HTTPS

```bash
sudo apt install -y nginx php-fpm certbot python3-certbot-nginx
sudo mkdir -p /var/www/html/melogin && sudo cp login.php logo.png /var/www/html/melogin/
```

`/etc/nginx/sites-available/default` (adjust the php-fpm socket version):

```nginx
server {
    listen 80 default_server;
    server_name _ your.domain.example;
    root /var/www/html;
    index index.php index.html;
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    }
}
```

```bash
sudo nginx -t && sudo systemctl reload nginx
sudo certbot --nginx -d your.domain.example --no-redirect   # adds HTTPS on 443
```

Important for the workshop:
- **Do not redirect HTTP to HTTPS and do not enable HSTS** (`--no-redirect` above), or the
  HTTP capture shows nothing.
- Use the **bare IP** for the HTTP link (`http://<server-ip>/melogin/login.php`) and the
  **domain** for HTTPS. Chrome's automatic HTTPS upgrade can turn `http://domain` into
  `https://domain`. Students should type `http://` explicitly.
