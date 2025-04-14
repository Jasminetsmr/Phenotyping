<?php
if ($_SESSION["level_user"] == "1") {
  include "left-sidebar-apoy.php";
} elseif ($_SESSION["level_user"] == "2") {
  include "left-sidebar-user.php";
}
?>