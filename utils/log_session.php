<?php
session_start();
 if (isset($_SESSION['user_id'])):  
?>
<span>
      <li>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></li>
      <li><a href="http://localhost/h26/admin/login/logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="/h26/admin/login/login.html">Login</a></li>
    <?php endif; ?>
</span>