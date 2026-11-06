<?php
// Demo login page for the "HTTP vs HTTPS" Wireshark workshop.
// Nothing is stored or checked against a real account: it only shows that
// the form data travels in plain text over HTTP and encrypted over HTTPS.
$posted = $_SERVER['REQUEST_METHOD'] === 'POST';
$user = $posted ? trim($_POST['username'] ?? '') : '';
$ok = $posted && $user === 'admin' && ($_POST['password'] ?? '') === '1234';  // demo account only
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Me Login – Workshop</title>
<style>
  :root { --teal: #0E7C6B; --orange: #C8650F; --dark: #222; --light: #E6F5F1; }
  * { box-sizing: border-box; }
  body { margin: 0; min-height: 100vh; display: grid; place-items: center;
         font-family: Arial, sans-serif; background: var(--light); color: var(--dark); }
  .card { width: min(360px, 92vw); background: #fff; border-radius: 14px; padding: 32px 28px;
          box-shadow: 0 6px 24px rgba(0,0,0,.08); text-align: center; }
  .logo { width: 96px; height: 96px; display: block; margin: 0 auto 12px; }
  h1 { margin: 0 0 4px; font-size: 22px; color: var(--teal); }
  .proto { display: inline-block; margin-bottom: 20px; padding: 3px 10px; border-radius: 99px;
           font-size: 13px; font-weight: bold; color: #fff; background: <?= $https ? 'var(--teal)' : 'var(--orange)' ?>; }
  input { width: 100%; padding: 11px 12px; margin-bottom: 12px; border: 1px solid #ccc;
          border-radius: 8px; font-size: 15px; }
  button { width: 100%; padding: 12px; border: 0; border-radius: 8px; background: var(--teal);
           color: #fff; font-size: 16px; font-weight: bold; cursor: pointer; }
  .msg { margin-top: 16px; padding: 10px; border-radius: 8px; background: var(--light); color: var(--teal); }
  .msg.fail { margin: 0 0 16px; background: #FDF0E2; color: var(--orange); }
  .warn { margin-top: 18px; font-size: 12px; color: #777; }
</style>
</head>
<body>
  <main class="card">
    <img class="logo" src="logo.png" alt="Me Live Code logo">
    <h1>Me Login</h1>
    <span class="proto"><?= $https ? 'HTTPS: encrypted' : 'HTTP: plain text' ?></span>
    <?php if ($ok): ?>
      <div class="msg">Login successful. Welcome, <b><?= htmlspecialchars($user) ?></b>!<br>Now check your Wireshark capture.</div>
      <p><a href="login.php">Back</a></p>
    <?php else: ?>
      <?php if ($posted): ?>
        <div class="msg fail">Login failed. Wrong username or password.</div>
      <?php endif; ?>
      <form method="post" action="login.php" autocomplete="off">
        <input name="username" placeholder="Username (e.g. admin)" required>
        <input name="password" type="password" placeholder="Password (e.g. 1234)" required>
        <button type="submit">Log in</button>
      </form>
    <?php endif; ?>
    <p class="warn">Workshop demo only. Do not type a real password.</p>
  </main>
</body>
</html>
