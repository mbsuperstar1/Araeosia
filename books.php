<?php
// Fetch the book number
$booknumber = $_POST['args[2]'];

include("includes/mysql.php");
include("includes/books.php");

// Finalize book time
$hours = floor($seconds / (60 * 60));
$divisor_for_minutes = $seconds % (60 * 60);
$minutes = floor($divisor_for_minutes / 60);
$divisor_for_seconds = $divisor_for_minutes % 60;
$seconds = ceil($divisor_for_seconds);

// Get completed books
mysql_query("SELECT * FROM bookscompleted WHERE name='$name'");

// Is a book currently being read?
$currentbooksql = mysql_query("SELECT * FROM books WHERE name='$name'") 
or die(mysql_error());
$currentbookrow = mysql_fetch_array( $currentbooksql );
$currentbookbeingread = $currentbookrow['$name'];
if ($currentbookbeingread == $name) {
	$type = "readtime";
	$oldbookid = $currentbookrow['booknumber'];
}
$time = time();
// Set type
if ($alreadyread != "true" && $stuff == "true") {
	$type = "readstart";
	mysql_query("INSERT INTO books (id, name, booknumber, bookstart) VALUES('NULL', '$name', '$booknumber', '$time'");
} elseif ( $alreadyread == "true") {
	$type = "alreadyread";
} elseif ( $alreadyread == "true") {
	$type = "queststart";
	if ( $questset == 1) {
		mysql_query("INSERT INTO permissions (id, name, type, permission) VALUES ('NULL', '$name', '1', 'book.completed.1.8')");
		mysql_query("INSERT INTO books (id, name, booknumber, bookstart) VALUES('NULL', '$name', '$booknumber', '$time')");
        }
}
		

// Output text to player
if ( $type == "readstart") {
	echo "You are now reading the Book of " . $bookname . ".";
	echo "It will take " . $booktimef . " to read.";
}
if ( $type == "readtime") {
	echo "You cannot read the Book of " . $bookname . " until you finish reading the Book of " . $oldbookname;
	echo "You will finish reading the Book of " . $oldbookname . " in " . $oldbooktime . ".";
}
if ( $type == "alreadyread") {
	echo "You cannot read the Book of " . $bookname . " since you have already read it!";
	echo "/Command/ExecuteConsoleCommand: give " . $name . " 1 flint:" . $damagevalue . ";";
}
?>