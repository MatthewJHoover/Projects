<?php
include("LIB_project1.php");
include("DB.class.php");
session_start();
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
$pageID = 3;
createNavigation($pageID);
?>
<div id="main">
<h2 class='heading'>Administer Inventory Page</h2>
	<div class="box"><table>
		<tr>
			<td>
				<div><form method='post'>
Choose an item to Edit: <select name='pickOne'>
<?php 
$db = new DB();
$db->addOptions();
?>
</select>
<input type='submit' name='edit' value='Select' />
</form></div>
			</td>
		</tr>
	</table>
	<br />
<?php
editForm($db);
?>
</div>
<div class="box">

			<form method='post' enctype="multipart/form-data">
				<table>
				   <tr><td colspan="2" class='areaHeading'>Add Item:</td></tr>
				   <tr>
					   <td>
						   Name:
					   </td>
					   <td>
						   <input type="text" name="name" size="40" value="" />

					   </td>
				   </tr>
				   <tr>
					   <td>
						   Description:
					   </td>
					   <td>
						   <textarea name="description" rows="3" cols="60"></textarea>
					   </td>
				   </tr>
				   <tr>
					   <td>
						   Price:
					   </td>
					   <td>
						   <input type="text" name="price" size="40" value="" />
					   </td>
				   </tr>
				   <tr>
					   <td>
						   Quantity on hand:
					   </td>
					   <td>
						   <input type="text" name="quantity" size="40" value="" />
					   </td>
				   </tr>
				   <tr>
					   <td>
						   Sale Price:
					   </td>
					   <td>
						   <input type="text" name="salesPrice" size="40" value="0" />
					   </td>
				   </tr>
				   <tr>
					   <td>
						   New Image:
					   </td>
					   <td>
						   <input type="file" name="image" />
					   </td>
				   </tr>
				   <tr>
					   <td>
								<strong>Your Username: </strong>
						</td>
						<td>
							<input type="text" name="username" size="15" />
						</td>
					</tr>
			   		<tr>
					   <td>
								<strong>Your Password: </strong>
						</td>
						<td>
							<input type="password" name="password" size="15" />
						</td>
					</tr>
			   </table>
			   <br />
			   <input type="reset" value="Reset Form" />
			   <input type="submit" name="submit_item" value="Submit Item" />
		   </form>
		   <br />
<?php
addForm($db);
?>
</div>
</div>
<?php
createFooter();
?>
</body>
</html>