<?php
/*
 * @author Shahrukh Khan
 * @website http://www.thesoftwareguy.in
 * @facebbok https://www.facebook.com/Thesoftwareguy7
 * @twitter https://twitter.com/thesoftwareguy7
 * @googleplus https://plus.google.com/+thesoftwareguyIn
 */

require("configure.php");
$state_id = ($_REQUEST["state_id"] <> "") ? trim($_REQUEST["state_id"]) : "";
if ($state_id <> "") {
    $sql = "SELECT * FROM tbl_city WHERE state_id = :sid ORDER BY cc_name";
    try {
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":sid", trim($state_id));
        $stmt->execute();
        $results = $stmt->fetchAll();
    } catch (Exception $ex) {
        echo($ex->getMessage());
    }
     if (count($results) > 0) {
        ?>
         
            
             
			 <?php foreach ($results as $rs) { ?>
			<tr><?php echo $rs["cc_name"]; ?><!-- <td><a id="demo-map" class="btn btn-default" data-latlng='34.056047,-118.2593786' href="#" role="button"<?php //echo $rs["map_name"]; ?>><img src="http://max-lab.in/ccenter/map.jpg" alt="" width="24" height="24"></a></td> --></tr>
			 <?php } ?>
				
            
       
        <?php
    }
}
?>