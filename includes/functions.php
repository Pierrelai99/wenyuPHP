<?php
/**
 * Generate next alphanumeric ID for a table.
 *
 * @param PDO $pdo - Database connection
 * @param string $table - Table name
 * @param string $column - Column name for ID
 * @param string $prefix - ID prefix, e.g., "C" or "SC"
 * @param int $length - Total length including prefix (optional)
 * @return string - New ID
 */
function generateNextId(PDO $pdo, string $table, string $column, string $prefix, int $length = 4): string {
    // Fetch the current maximum numeric part for the given prefix
    $stmt = $pdo->prepare("
        SELECT MAX(CAST(SUBSTRING($column, :prefixLen+1) AS UNSIGNED)) AS max_id
        FROM $table
        WHERE $column LIKE :prefixLike
    ");
    $prefixLen = strlen($prefix);
    $stmt->execute([
        ':prefixLen' => $prefixLen,
        ':prefixLike' => $prefix . '%'
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxId = $row['max_id'] ?? 0;

    // Increment numeric part
    $nextNum = (int)$maxId + 1;

    // Pad with leading zeros
    $numPartLength = $length - $prefixLen;
    $numPart = str_pad((string)$nextNum, $numPartLength, '0', STR_PAD_LEFT);

    return $prefix . $numPart;
}
