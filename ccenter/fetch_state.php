<?php
/*
 * @author Shahrukh Khan
 * @website http://www.thesoftwareguy.in
 * @facebbok https://www.facebook.com/Thesoftwareguy7
 * @twitter https://twitter.com/thesoftwareguy7
 * @googleplus https://plus.google.com/+thesoftwareguyIn
 */


require("configure.php");
$country_id = ($_REQUEST["country_id"] <> "") ? trim($_REQUEST["country_id"]) : "";
if ($country_id <> "") {
    $sql = "SELECT * FROM tbl_state WHERE country_id = :cid ORDER BY state_name";
    try {
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":cid", trim($country_id));
        $stmt->execute();
        $results = $stmt->fetchAll();
    } catch (Exception $ex) {
        echo($ex->getMessage());
    }
    if (count($results) > 0) {
        ?>
        <label><strong>Select Region </strong>
            <select name="state" onchange="showCity(this);">
                <option value="">Please Select</option>
                <?php foreach ($results as $rs) { ?>
                    <option value="<?php echo $rs["id"]; ?>"><?php echo $rs["state_name"]; ?></option>
                <?php } ?>
            </select>
        </label>
        <?php
    }
}
?>