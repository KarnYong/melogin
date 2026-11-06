# Me Login: HTTP vs HTTPS workshop site

Demo login page for the Network Security workshop "HTTP vs HTTPS" (Wireshark).
The only valid login is **admin / 1234**; anything else shows "Login failed". Nothing is stored.

## Live sites

| Protocol | URL |
|---|---|
| HTTP (plain text) | http://187.127.215.16/login.php |
| HTTPS (encrypted) | https://melogin.melivecode.com/login.php |

Students should type `http://` explicitly for the HTTP site. Chrome's automatic HTTPS upgrade can
change `http://<domain>` into `https://`, which is why the HTTP link uses the bare IP.

## Files

- `login.php`: the page (POST form with fields `username` and `password`; shows HTTP/HTTPS badge).
- `logo.png`: logo shown in the middle of the card.
- `SERVER_SETUP_PROMPT.md`: ready-to-paste prompt for an AI agent to set up a new Ubuntu server.

## Server setup (Ubuntu, nginx + PHP)

See `SERVER_SETUP_PROMPT.md`. In short: the repo is cloned on the server and served from the web
root, so the page is at `/login.php`. Port 80 serves the bare IP and the domain; certbot adds
HTTPS on 443 with `--no-redirect`.

Important for the workshop:
- **Do not redirect HTTP to HTTPS and do not enable HSTS**, or the HTTP capture shows nothing.
- Keep nginx from serving the `.git` folder.

## Updating

Push to GitHub, then on the server: `sudo melogin-update` (runs `git pull --ff-only`).
