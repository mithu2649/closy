<?php
    include('./classes/DB.php');
    include('./classes/Login.php');
    if (!Login::isLoggedIn()) {
            die("Not logged in.");
    }
    if (isset($_POST['confirm'])) {
            if (isset($_POST['alldevices'])) {
                    DB::query('DELETE FROM login_tokens WHERE user_id=:user_id', array(':user_id'=>Login::isLoggedIn()));
            } else {
                    if (isset($_COOKIE['CLID'])) {
                            DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['CLID'])));
                    }
                    setcookie('CLID', '1', time()-3600);
                    setcookie('CLID_', '1', time()-3600);
            }
    }
?>
<h1>Logout of your Account?</h1>
<p>Are you sure you'd like to logout?</p>
<form action="logout.php" method="post">
        <input type="checkbox" name="alldevices" value="alldevices">Log me out of all devices.<br />
        <input type="submit" name="confirm" value="Confirm">
</form>