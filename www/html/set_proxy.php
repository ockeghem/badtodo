<?php
  $host = $_SERVER["HTTP_HOST"];
?><body>
  今接続しているポートはProxy用のものです。<br>
  <?php echo htmlspecialchars($host); ?> をProxyをセットしてください。<br>
  お勧めは、お使いのローカルプロキシ（Burp SuiteやOWASP Zap等）のリモートプロキシ設定を<br>
  <?php echo htmlspecialchars($host); ?> にセットしていただくとよいと思います。<br>
  Proxyをセットしたら、以下にアクセスすると実習が開始できます。<br><br>
  <a href="https://todo.example.jp">実習開始ページ（HTTPS）</a><br>
  <a href="http://todo.example.jp">実習開始ページ（HTTP）</a><br><br>
  Burp Suite組み込みのChromiumでアクセスしている場合は、SSL証明書の設定ずみなので、HTTPSでアクセスすることをお勧めします。
</body>