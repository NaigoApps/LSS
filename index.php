<?php require_once __DIR__ . '/common/php/consts.php'; ?>
<?php require_once __DIR__ . '/common/auth-header.php'; ?>

<?php
if (isset($_SESSION['user_data'])) {
    if ($_SESSION['user_data']->isAdmin()) {
        header('Location: ' . WEB . '/professors/main.php');
    } elseif ($_SESSION['user_data']->isProfessor()) {
        header('Location: ' . WEB . '/professors/main.php');
    } elseif ($_SESSION['user_data']->isStudent()) {
        header('Location: ' . WEB . '/students/main.php');
    } elseif ($_SESSION['user_data']->isUnknown()) {
        header('Location: ' . WEB . '/waiting/main.php');
    } elseif ($_SESSION['user_data']->isUnregistered()) {
        header('Location: ' . WEB . '/register/main.php');
    } else {          
        header('Location: ' . WEB . '/register/main.php');
    }
}