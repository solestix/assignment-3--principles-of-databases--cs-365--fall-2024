<?php
include 'config.php';

function search($search) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER
        );
        $db->exec("SET block_encryption_mode = 'aes-256-cbc';");
        $select_query = "
            SELECT 'user' AS table_name, user_id AS id, first_name AS attribute1, last_name AS attribute2, email AS attribute3, NULL AS attribute4, NULL AS attribute5
            FROM user
            WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search
            UNION
            SELECT 'website' AS table_name, site_id AS id, site_name AS attribute1, domain AS attribute2, NULL AS attribute3, NULL AS attribute4, NULL AS attribute5
            FROM website
            WHERE site_name LIKE :search OR domain LIKE :search
            UNION
            SELECT 'password' AS table_name, user_id AS id, site_id AS attribute1, username AS attribute2,
                   AES_DECRYPT(password, UNHEX(SHA2('nothing to see here', 256)), '1234567890ABCDEF') AS attribute3, time_created AS attribute4, comment AS attribute5
            FROM password
            WHERE username LIKE :search OR comment LIKE :search
        ";
        $statement = $db -> prepare($select_query);
        $statement -> execute([':search' => "%{$search}%"]);

        $results = $statement -> fetchAll(PDO::FETCH_ASSOC);

        if (count($results) == 0) {
            echo "<p>No results found for '{$search}'.</p>";
        } else {
            echo "      <table>\n";
            echo "        <thead>\n";
            echo "          <tr>\n";
            // Dynamically generate table headers
            if (!empty($results)) {
                foreach (array_keys($results[0]) as $header) {
                    echo "            <th>" . htmlspecialchars($header) . "</th>\n";
                }
            }
            echo "          </tr>\n";
            echo "        </thead>\n";
            echo "        <tbody>\n";

            // Populate the table with data coming from the database...
            foreach ($results as $row) {
                echo "          <tr>\n";
                foreach ($row as $cell) {
                    echo "            <td>" . htmlspecialchars($cell ?? '') . "</td>\n";
                }
                echo "          </tr>\n";
            }

            echo "         </tbody>\n";
            echo "      </table>\n";
        }
    } catch( PDOException $e ) {
        echo '<p>The following message was generated by function <code>search</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";
        exit;
    }
}

function update($table, $current_attribute, $new_attribute, $query_attribute, $pattern) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER
        );
        $db->exec("SET block_encryption_mode = 'aes-256-cbc';");

        // Encrypt the password if the current attribute is 'password'
        if ($current_attribute == 'password') {
            $statement = $db -> prepare("SELECT AES_ENCRYPT(:password, UNHEX(SHA2('nothing to see here', 256)), '1234567890ABCDEF') AS encrypted_password");
            $statement -> execute([
                ':password' => $new_attribute
            ]);
            $result = $statement -> fetch(PDO::FETCH_ASSOC);
            $new_attribute = $result['encrypted_password'];
        }

        $db -> query("UPDATE {$table} SET {$current_attribute}=\"{$new_attribute}\" WHERE {$query_attribute}=\"{$pattern}\"");
    } catch( PDOException $e ) {
        echo '<p>The following message was generated by function <code>update</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    }
}

function insert($table, $data) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
        );
        $db->exec("SET block_encryption_mode = 'aes-256-cbc';");
        // Manually handle primary keys
        if ($table == 'user') {
            if (!isset($data['user_id'])) {
                $data['user_id'] = getNextId($db, 'user', 'user_id');
            }
        } elseif ($table == 'website') {
            if (!isset($data['site_id'])) {
                $data['site_id'] = getNextId($db, 'website', 'site_id');
            }
        } elseif ($table == 'password') {
            if (!isset($data['user_id']) || !isset($data['site_id'])) {
                throw new Exception('Primary keys user_id and site_id must be provided for the password table.');
            }
            // Automatically set the time_created field to NOW()
            $data['time_created'] = date('Y-m-d H:i:s');

            // Encrypt the password
            $statement = $db -> prepare("SELECT AES_ENCRYPT(:password, UNHEX(SHA2('nothing to see here', 256)), '1234567890ABCDEF') AS encrypted_password");
            $statement -> execute([
                ':password' => $data['password']
            ]);
            $result = $statement -> fetch(PDO::FETCH_ASSOC);
            $data['password'] = $result['encrypted_password'];
        }

        $columns = array_keys($data);
        $values = array_values($data);

        $columns_str = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($values), "?"));
        $statement = $db -> prepare("INSERT INTO {$table} ({$columns_str}) VALUES ({$placeholders})");
        $statement -> execute($values);
    } catch(PDOException $e) {
        echo '<p>The following message was generated by function <code>insert</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    } catch(Exception $e) {
        echo '<p>The following message was generated by function <code>insert</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    }
}

function delete($table, $current_attribute, $pattern) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER
        );

        $db -> query("DELETE FROM {$table} WHERE {$current_attribute}=\"{$pattern}\"");
    } catch(PDOException $e) {
        echo '<p>The following message was generated by function <code>delete</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    }
}

function getNextId($db, $table, $column) {
    $statement = $db -> prepare("SELECT MAX({$column}) AS max_id FROM {$table}");
    $statement -> execute();
    $result = $statement -> fetch(PDO::FETCH_ASSOC);
    return $result['max_id'] + 1;
}
?>
