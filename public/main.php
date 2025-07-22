<?php
include '../sessions/session.php';
requireLogin();
?>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

<?php if ($_SESSION['is_admin']): ?>
    <a href="admin_panel.php">Admin Panel</a>
<?php endif; ?>

<!-- Your user creation form goes here -->

<a href="logout.php">Logout</a>
