<?php
include "top.php";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// SECTION: 1a.
// variables for the classroom purposes to help find errors.
$debug = false;//true;

if (isset($_POST["debug"])) { // ONLY do this in a classroom environment
    $debug = true;
}

if ($debug)
    print "<p>DEBUG MODE IS ON</p>";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// SECTION: 1b Security
// define security variable to be used in SECTION 2a.
$yourURL = $domain . $phpSelf;

?>
<article id="main">
    <h1>Pets Available for Adoption</h1>
    <?php
    
//query
$query = "SELECT P.fldPetName, P.fldCategory, ";
$query .= "P.fldAge, P.fldGender, P.fldColor, ";
$query .= "P.fldImgName, P.fldDateAdded, ";
$query .= "P.fldDescription, P.pmkPetId, ";
$query .= "P.fnkRegisterId ";
$query .= "FROM tblPets P, tblRegister R ";
$query .= "WHERE (P.fnkRegisterId = R.pmkRegisterId) ";// AND (fldImgName  = 'resize_chessie.JPG') ";
$query .= "ORDER BY fldPetName ASC ";

if ($debug) {
    print "<p>query: " . $query . "</p>";
    }
$results = $thisDatabase->select($query);



?><!-- <article id="main">
    <h1>Pets Available for Adoption</h1>
    <p>this will show info about the pets and pix</p> --><?php
foreach ($results as $row){
    //print '<p class="groove">';
    print '<div style="border:10px groove #98bf21;padding:30px;">'; //width:93%;
    print "\r\n<h1>Name: " . $row["fldPetName"] . "</h1>\r\n"; 
    print "<p>Age: " . $row["fldAge"] . "</p>\r\n"; 
    print "<p>Gender: " . $row["fldGender"] . "</p>\r\n"; 
    print "<p>Description: " . $row["fldDescription"] . "</p>\r\n"; 
    print "<p>Color: " . $row["fldColor"] . "</p>\r\n"; 
    print "<p>Type of pet: " . $row["fldCategory"] . "</p>\r\n"; 
    print "<p>Unique pet ID: " . $row["pmkPetId"] . "</p>\r\n"; 
    print "<p>Submitted by User ID: " . $row["fnkRegisterId"] . "</p>\r\n";
    print '<p><a href="contact.php?id=' . $row["fnkRegisterId"] . '">[Click to Contact User]</a></p>';
    //print "<p>Image Name: " . $row["fldImgName"] . "</p>\r\n"; 
    print "\r\n";
    print '<p><img src="petpix/' . $row["fldImgName"] . '" alt="' . $row["fldPetName"] . '-image" /></p>';
    //print '<p><img src="petpix/resize_chessie.JPG" alt="image" /></p>';
    print "</div>";
}

?>
</article>

<?php include "footer.php"; ?>

</body>
</html>

