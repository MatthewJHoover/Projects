<?php
include("LIB_project1.php");
include("DB.class.php");
?>
<!DOCTYPE html>
<html>
<head>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<title>Pizza Shop</title>
<script type="text/javascript">
  var myip;
</script>
<script type="text/javascript" src="https://l2.io/ip.js?var=myip"></script>
<script>
var nVer = navigator.appVersion;
var nAgt = navigator.userAgent;
var browserName  = navigator.appName;
var fullVersion  = ''+parseFloat(navigator.appVersion); 
var majorVersion = parseInt(navigator.appVersion,10);
var nameOffset,verOffset,ix;

if ((verOffset=nAgt.indexOf("OPR/"))!=-1) {
 browserName = "Opera";
 fullVersion = nAgt.substring(verOffset+4);
}

else if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
 browserName = "Opera";
 fullVersion = nAgt.substring(verOffset+6);
 if ((verOffset=nAgt.indexOf("Version"))!=-1) 
   fullVersion = nAgt.substring(verOffset+8);
}

else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
 browserName = "Microsoft Internet Explorer";
 fullVersion = nAgt.substring(verOffset+5);
}

else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
 browserName = "Chrome";
 fullVersion = nAgt.substring(verOffset+7);
}

else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
 browserName = "Safari";
 fullVersion = nAgt.substring(verOffset+7);
 if ((verOffset=nAgt.indexOf("Version"))!=-1) 
   fullVersion = nAgt.substring(verOffset+8);
}

else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
 browserName = "Firefox";
 fullVersion = nAgt.substring(verOffset+8);
}

else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) < 
          (verOffset=nAgt.lastIndexOf('/')) ) 
{
 browserName = nAgt.substring(nameOffset,verOffset);
 fullVersion = nAgt.substring(verOffset+1);
 if (browserName.toLowerCase()==browserName.toUpperCase()) {
  browserName = navigator.appName;
 }
}

if ((ix=fullVersion.indexOf(";"))!=-1)
   fullVersion=fullVersion.substring(0,ix);
if ((ix=fullVersion.indexOf(" "))!=-1)
   fullVersion=fullVersion.substring(0,ix);

majorVersion = parseInt(''+fullVersion,10);
if (isNaN(majorVersion)) {
 fullVersion  = ''+parseFloat(navigator.appVersion); 
 majorVersion = parseInt(navigator.appVersion,10);
}
</script>
</head>
<body>
<?php
createBanner();
$pageID = 1;
createNavigation($pageID);
?>
<div id="main">
<?php
$db = new DB();
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$id = $_POST['item'];
	$db->addUpdate($id);
}
?>
<h2 class='heading'>Sale Items</h2>
<div id='sales'>
<?php
echo $db->getSaleItems();
?>
</div>
<div class='clear'></div><h2 class='heading'>Catalog</h2>
<div id='items'>
<?php
echo $db->getCatalog();
?>
<div id='items_controls'>
<?php
createControls($db);
?>
</div>
</div>
<div class='clear'></div></div>
<?php
createFooter();
?>
</body>
</html>