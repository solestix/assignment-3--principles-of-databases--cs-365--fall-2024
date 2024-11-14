<?php
include 'includes/helpers.php';

$tables = [
    'user' => ['user_id', 'first_name', 'last_name', 'email'],
    'website' => ['site_id', 'site_name', 'domain'],
    'password' => ['user_id', 'site_id', 'username', 'password', 'comment']
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
        search($search);
    } elseif (isset($_POST['update'])) {
        $table = $_POST['table'];
        $current_attribute = $_POST['current_attribute'];
        $new_attribute = $_POST['new_attribute'];
        $query_attribute = $_POST['query_attribute'];
        $pattern = $_POST['pattern'];
        update($table, $current_attribute, $new_attribute, $query_attribute, $pattern);
    } elseif (isset($_POST['insert'])) {
        $table = $_POST['table'];
        $data = [];
        foreach ($tables[$table] as $column) {
            if (isset($_POST[$column])) {
                $data[$column] = $_POST[$column];
            }
        }
        insert($table, $data);
    } elseif (isset($_POST['delete'])) {
        $table = $_POST['table'];
        $current_attribute = $_POST['current_attribute'];
        $pattern = $_POST['pattern'];
        delete($table, $current_attribute, $pattern);
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
        <label for="table">Table:</label>
        <select name="table" id="table">
            <?php foreach ($tables as $table => $attributes): ?>
                <option value="<?= $table ?>"><?= ucfirst($table) ?></option>
            <?php endforeach; ?>
        </select>
        <label for="current_attribute">Current Attribute:</label>
        <select name="current_attribute" id="current_attribute">
            <?php foreach ($tables['user'] as $attribute): ?>
                <option value="<?= $attribute ?>"><?= $attribute ?></option>
            <?php endforeach; ?>
        </select>
        <label for="new_attribute">New Attribute:</label>
        <input type="text" name="new_attribute" placeholder="New Attribute">
        <label for="query_attribute">Query Attribute:</label>
        <select name="query_attribute" id="query_attribute">
            <?php foreach ($tables['user'] as $attribute): ?>
                <option value="<?= $attribute ?>"><?= $attribute ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="pattern" placeholder="Pattern">
        <button type="submit">Update</button>
    </form>

    <h2>Insert into User</h2>
    <form method="post">
        <input type="hidden" name="insert" value="1">
        <input type="hidden" name="table" value="user">
        <?php foreach ($tables['user'] as $attribute): ?>
            <label for="<?= $attribute ?>"><?= ucfirst($attribute) ?>:</label>
            <input type="text" name="<?= $attribute ?>" id="<?= $attribute ?>" placeholder="<?= ucfirst($attribute) ?>">
        <?php endforeach; ?>
        <button type="submit">Insert</button>
    </form>

    <h2>Insert into Website</h2>
    <form method="post">
        <input type="hidden" name="insert" value="1">
        <input type="hidden" name="table" value="website">
        <?php foreach ($tables['website'] as $attribute): ?>
            <label for="<?= $attribute ?>"><?= ucfirst($attribute) ?>:</label>
            <input type="text" name="<?= $attribute ?>" id="<?= $attribute ?>" placeholder="<?= ucfirst($attribute) ?>">
        <?php endforeach; ?>
        <button type="submit">Insert</button>
    </form>

    <h2>Insert into Password</h2>
    <form method="post">
        <input type="hidden" name="insert" value="1">
        <input type="hidden" name="table" value="password">
        <?php foreach ($tables['password'] as $attribute): ?>
            <label for="<?= $attribute ?>"><?= ucfirst($attribute) ?>:</label>
            <input type="text" name="<?= $attribute ?>" id="<?= $attribute ?>" placeholder="<?= ucfirst($attribute) ?>">
        <?php endforeach; ?>
        <button type="submit">Insert</button>
    </form>

    <h2>Delete</h2>
    <form method="post">
        <input type="hidden" name="delete" value="1">
        <label for="table">Table:</label>
        <select name="table" id="table">
            <?php foreach ($tables as $table => $attributes): ?>
                <option value="<?= $table ?>"><?= ucfirst($table) ?></option>
            <?php endforeach; ?>
        </select>
        <label for="current_attribute">Current Attribute:</label>
        <select name="current_attribute" id="current_attribute">
            <?php foreach ($tables['user'] as $attribute): ?>
                <option value="<?= $attribute ?>"><?= $attribute ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="pattern" placeholder="Pattern">
        <button type="submit">Delete</button>
    </form>
</body>
</html>
