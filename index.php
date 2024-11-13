<?php
include 'includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
        search($search);
    } elseif (isset($_POST['update'])) {
        $current_attribute = $_POST['current_attribute'];
        $new_attribute = $_POST['new_attribute'];
        $query_attribute = $_POST['query_attribute'];
        $pattern = $_POST['pattern'];
        update($current_attribute, $new_attribute, $query_attribute, $pattern);
    } elseif (isset($_POST['insert'])) {
        $user_id = $_POST['user_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        insert($user_id, $first_name, $last_name, $email);
    } elseif (isset($_POST['delete'])) {
        $current_attribute = $_POST['current_attribute'];
        $pattern = $_POST['pattern'];
        delete($current_attribute, $pattern);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Management</title>
</head>
<body>
    <h1>Database Management</h1>

    <h2>Search</h2>
    <form method="post">
        <input type="text" name="search" placeholder="Search...">
        <button type="submit">Search</button>
    </form>

    <h2>Update</h2>
    <form method="post">
        <input type="hidden" name="update" value="1">
        <input type="text" name="current_attribute" placeholder="Current Attribute">
        <input type="text" name="new_attribute" placeholder="New Attribute">
        <input type="text" name="query_attribute" placeholder="Query Attribute">
        <input type="text" name="pattern" placeholder="Pattern">
        <button type="submit">Update</button>
    </form>

    <h2>Insert</h2>
    <form method="post">
        <input type="hidden" name="insert" value="1">
        <input type="text" name="user_id" placeholder="User ID">
        <input type="text" name="first_name" placeholder="First Name">
        <input type="text" name="last_name" placeholder="Last Name">
        <input type="text" name="email" placeholder="Email">
        <button type="submit">Insert</button>
    </form>

    <h2>Delete</h2>
    <form method="post">
        <input type="hidden" name="delete" value="1">
        <input type="text" name="current_attribute" placeholder="Current Attribute">
        <input type="text" name="pattern" placeholder="Pattern">
        <button type="submit">Delete</button>
    </form>
</body>
</html>
