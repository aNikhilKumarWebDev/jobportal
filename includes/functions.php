<?php
function executeQuery($conn, $query)
{
    return $conn->query($query);
}
