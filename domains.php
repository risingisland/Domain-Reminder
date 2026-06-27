<?php
    session_start();
    require_once("includes/dbconnect.php");
    require_once("includes/functions.php");
    require_once("includes/languages.php");
	require_once("config/version.php"); //Load the version number
	
    $msg = "";
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: index.php"); exit();
    }

    $filter = "";
    $bgClass = "even";
    $files_table = "";

    $_SESSION["adm_domains.orderBy"] = (!empty($_REQUEST["orderBy"])) ? $_REQUEST["orderBy"] : (!empty($_SESSION["adm_domains.orderBy"]) ? $_SESSION["adm_domains.orderBy"] : "renewalDate");
    $_SESSION["adm_domains.direction"] = (!empty($_REQUEST["direction"])) ? $_REQUEST["direction"] : (!empty($_SESSION["adm_domains.direction"]) ? $_SESSION["adm_domains.direction"] : "ASC");

    // Whitelist sort columns
    $allowed_cols = ["domain","clientID","registrationDate","renewalDate","registrar","id"];
    $orby = in_array($_SESSION["adm_domains.orderBy"], $allowed_cols) ? $_SESSION["adm_domains.orderBy"] : "renewalDate";
    $didi = ($_SESSION["adm_domains.direction"] === "DESC") ? "DESC" : "ASC";
    $filter .= " ORDER BY $orby $didi";

    $rowsPerPage = 99999;
    $pageNum = isset($_REQUEST["page"]) ? max(1, (int)$_REQUEST["page"]) : 1;
    $offset = ($pageNum - 1) * $rowsPerPage;

    $count_stmt = $pdo->query("SELECT COUNT(id) AS numrows FROM adm_domains");
    $numrows = (int)$count_stmt->fetchColumn();
    $maxPage = max(1, ceil($numrows / $rowsPerPage));

    $self = $_SERVER["PHP_SELF"];
    $nav = "";
    for ($page = 1; $page <= $maxPage; $page++) {
        if ($page == $pageNum) {
            $nav .= " <li class=\"page-item active\"> <span class=\"page-link\"> $page </span></li>";
        } else {
            $nav .= " <li class=\"page-item\"><a href=\"$self?page=$page\" class=\"page-link\">$page</a></li> ";
        }
    }
    $prev = $first = $next = $last = "&nbsp;";
    if ($pageNum > 1) {
        $page = $pageNum - 1;
        $prev = " <li class=\"page-item\"><a href=\"$self?page=$page\" class=\"page-link\"><i class=\"fas fa-angle-left\"></i></a></li> ";
        $first = " <li class=\"page-item\"><a href=\"$self?page=1\" class=\"page-link\"><i class=\"fas fa-angle-double-left\"></i></a></li> ";
    }
    if ($pageNum < $maxPage) {
        $page = $pageNum + 1;
        $next = " <li class=\"page-item\"><a href=\"$self?page=$page\" class=\"page-link\"><i class=\"fas fa-angle-right\"></i></a></li> ";
        $last = " <li class=\"page-item\"><a href=\"$self?page=$maxPage\" class=\"page-link\"><i class=\"fas fa-angle-double-right\"></i></a></li> ";
    }

    if (!empty($_REQUEST["files_delete"]) && $_REQUEST["files_delete"] == "yes" && isset($_POST["filesToDel"]) && is_array($_POST["filesToDel"])) {
        $ids = array_map("intval", $_POST["filesToDel"]);
        $ids = array_filter($ids);
        if (!empty($ids)) {
            $placeholders = implode(",", array_fill(0, count($ids), "?"));
            $pdo->prepare("DELETE FROM adm_domains WHERE id IN ($placeholders)")->execute($ids);
            $msg = "<div class=\"alert alert-success alert-dismissible\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><h5><i class=\"icon fas fa-check\"></i> " . $lang["DOMAINS_DELETED"] . "</h5></div>";
            foreach ($ids as $vid) {
                $domain_name_temp = getDomainNameById($vid);
                $jks = xorEncrypt($domain_name_temp, strtoupper($domain_name_temp));
                $pdo->prepare("DELETE FROM adm_data WHERE dd = ?")->execute([$jks]);
            }
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM adm_domains $filter LIMIT $offset, $rowsPerPage");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    if (count($rows) > 0) {
        foreach ($rows as $rr) {
            $editable = "<a href=\"domains-edit.php?id=" . $rr["id"] . "\"><i class=\"fas fa-edit\" title=\"" . $lang["EDIT"] . "\"></i></a>";
            $files_table .= "<tr>";
            $files_table .= "<td style=\"text-align: center;\"><input name=\"filesToDel[]\" type=\"checkbox\" value=\"" . $rr["id"] . "\" /></td>";
            $files_table .= "<td><a href=\"domains-edit.php?id=" . $rr["id"] . "\">" . htmlspecialchars($rr["domain"]) . "</a></td>";
            $files_table .= "<td class=\"company-name\">" . htmlspecialchars((string)getClientName((int)$rr["clientID"])) . "</td>";
            $files_table .= "<td>" . ($rr["registrationDate"] != "0000-00-00" && $rr["registrationDate"] ? date("d M, Y", strtotime($rr["registrationDate"])) : "------") . "</td>";
            $files_table .= "<td>" . ($rr["renewalDate"] != "0000-00-00" && $rr["renewalDate"] ? date("d M, Y", strtotime($rr["renewalDate"])) : "------") . "</td>";
            $files_table .= "<td>" . htmlspecialchars((string)$rr["registrar"]) . "</td>";
            $files_table .= "<td>" . $editable . "</td></tr>";
        }
        $files_table .= "<tr><td><button type=\"submit\" name=\"delete_files\" class=\"btn btn-block btn-danger del-confirm\" title=\"" . $lang["DELETE_SELECTED"] . "\"><i class=\"fas fa-trash-alt\"></i></button></td></tr>";
    } else {
        $files_table .= "<tr><td colspan=\"7\">0 domains found in database</td></tr>";
    }
    $direction = ($_SESSION["adm_domains.direction"] === "DESC") ? "ASC" : "DESC";
?>
<!DOCTYPE html><html lang="<?php echo $lang['LANG_CODE']; ?>">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $lang['SITE_TITLE']; ?></title>
<link rel="icon" type="image/png" sizes="96x96" href="assets/img/domain.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="assets/js/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="assets/css/adminlte.min.css">
<link rel="stylesheet" href="assets/css/custome.css">
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper">
			<!-- Navbar -->
			<nav class="main-header navbar navbar-expand navbar-white navbar-light">
				<!-- Left navbar links -->
				<ul class="navbar-nav">
					<li class="nav-item"> <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a> </li>
				</ul>
				<!-- SEARCH FORM -->
				<form class="form-inline ml-3">
					<div class="input-group input-group-sm">
						<input class="form-control form-control-navbar search-bar" type="search" placeholder="<?php echo $lang['SEARCH']; ?>" aria-label="Search">
						<div class="input-group-append">
							<button class="btn btn-navbar no-click" type="submit"> <i class="fas fa-search"></i> </button>
						</div>
					</div>
				</form>
				<!-- Right navbar links -->
				<ul class="navbar-nav ml-auto">
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="fas fa-user-circle fa-2x text-primary"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<span class="dropdown-header"><?php echo $lang['ADMIN']; ?></span>
						<div class="dropdown-divider"></div>
						<a href="settings.php" class="dropdown-item">
							<i class="fas fa-cogs mr-2 text-info"></i> <?php echo $lang['SETTINGS']; ?>
						</a>
						<div class="dropdown-divider"></div>
						<a href="logout.php" class="dropdown-item">
							<i class="fas fa-sign-out-alt mr-2 text-danger"></i> <?php echo $lang['SIGNOUT']; ?>
						</a>
					</li>
				</ul>
			</nav>
			<!-- /.navbar -->
			<!-- Main Sidebar Container -->
			<aside class="main-sidebar sidebar-dark-primary elevation-4">
				<!-- Brand Logo -->
				<a href="dashboard.php" class="brand-link"> <img src="assets/img/domain.png" alt="Logo" class="brand-image"> <span class="brand-text" style="color:#1E81CE;"><b><?php echo $lang['SITE_TITLE']; ?></b></span> </a>
				<!-- Sidebar -->
				<div class="sidebar">
					<div class="user-panel mt-3 pb-3 mb-3 d-flex"><div>&nbsp;</div></div>
					<nav class="mt-2">
						<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
							<li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p> <?php echo $lang['DASHBOARD']; ?> </p></a></li>
							<li class="nav-item"><a href="domains.php" class="nav-link active"><i class="nav-icon fas fa-globe"></i><p> <?php echo $lang['DOMAINS']; ?> </p></a></li>
							<li class="nav-item"><a href="domains-expiring.php" class="nav-link"><i class="nav-icon fas fa-flag"></i><p> <?php echo $lang['EXPIRING_SOON']; ?> <span class="right badge badge-warning"><?php echo checkHowManyExpires();?></span></p></a></li>
							<li class="nav-item"><a href="clients.php" class="nav-link"><i class="nav-icon fas fa-users"></i><p> <?php echo $lang['CLIENTS']; ?> </p></a></li>
							<li class="nav-item"><a href="clients-add.php" class="nav-link"><i class="nav-icon fas fa-user-plus"></i><p> <?php echo $lang['ADD_CLIENT']; ?> </p></a></li>
							<li class="nav-item"><a href="backup.php" class="nav-link"><i class="nav-icon fas fa-database"></i><p> <?php echo $lang['DATABASES']; ?> </p></a></li>
							<li class="nav-item"><a href="help.php" class="nav-link"><i class="nav-icon fas fa-question-circle"></i><p> <?php echo $lang['HELP']; ?> </p></a></li>
						</ul>
					</nav>
				</div>
				<!-- /.sidebar -->
			</aside>
<div class="content-wrapper">
  <div class="content-header"><div class="container-fluid"><div class="row mb-2"><div class="col-sm-12">
    <h1 class="m-0 text-primary"><i class="fas fa-globe"></i> <?php echo $lang['DOMAINS']; ?></h1>
  </div></div></div></div>
  <style>.light-green{background-color:rgba(0,204,0,.2)}.light-yellow{background-color:rgba(255,204,0,.2)}.light-red{background-color:rgba(255,0,0,.2)}</style>
  <section class="content">
    <div class="col-md-6"><?php echo $msg; ?></div>
    <div class="col-lg-12"><div class="card card-primary card-outline"><div class="card-body p-0">
      <form enctype="multipart/form-data" action="domains.php" method="post" name="ff2">
        <input type="hidden" value="yes" name="files_delete" />
        <div class="fixTableHead"><table class="table table-striped"><thead><tr>
          <th>&nbsp;</th>
          <th><strong><a onclick="document.ff2.orderBy.value='domain'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['DOMAIN_NAME']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="domain"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>
          <th><strong><a onclick="document.ff2.orderBy.value='clientID'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['CLIENT_NAME']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="clientID"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>
          <th><strong><a onclick="document.ff2.orderBy.value='registrationDate'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['REGISTRATION_DATE']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="registrationDate"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>
          <th><strong><a onclick="document.ff2.orderBy.value='renewalDate'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['RENEWAL_DATE']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="renewalDate"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>
          <th><strong><a onclick="document.ff2.orderBy.value='registrar'; document.ff2.direction.value='<?php echo $direction?>'; document.ff2.submit();" style="cursor:pointer;"><?php echo $lang['REGISTRAR']; ?></a>&nbsp;<?php if($_SESSION["adm_domains.orderBy"]=="registrar"){ ?><img src="assets/img/<?php echo strtolower($direction)?>.png" /><?php } ?></strong></th>
          <th><?php echo $lang['EDIT']; ?></th>
        </tr></thead>
        <tbody>
          <?php echo $files_table; ?>
          <tr><td colspan="7" align="right" class="paging">
            <nav><ul class="pagination justify-content-center m-0"><?php echo $first . $prev . $nav . $next . $last; ?></ul></nav>
          </td></tr>
        </tbody></table></div>
        <input type="hidden" name="orderBy" value="<?php echo htmlspecialchars($_SESSION["adm_domains.orderBy"]); ?>">
        <input type="hidden" name="direction" value="<?php echo htmlspecialchars($_SESSION["adm_domains.direction"]); ?>">
      </form>
    </div></div></div>
  </section>
</div>
<footer class="main-footer">
  <div class="float-right d-none d-sm-block"><p><b><?php echo $lang['VERSION']; ?>:</b> <?php echo $version; ?></p></div>
  <p><strong>Copyright &copy; <?php echo date("Y"); ?> <?php echo $lang['FOOTER_CREDITS']; ?></p>
</footer>
</div>
<script src="assets/js/jquery/jquery.min.js"></script>
<script src="assets/js/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/adminlte.min.js"></script>
<script src="assets/js/del-confirm.js"></script>
<script src="assets/js/filter-domain-client.js"></script>
</body></html>