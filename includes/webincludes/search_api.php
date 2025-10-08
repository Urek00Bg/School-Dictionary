<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');
$grade = isset($_GET['grade']) ? (int)$_GET['grade'] : 0;

try {
    // Exact match check first (only if user typed something)
    if ($q !== '') {
        $stmtExact = $pdo->prepare("
            SELECT 
                id,
                COALESCE(word, '') AS word,
                COALESCE(word_sl, '') AS word_sl,
                COALESCE(definition, '') AS definition,
                COALESCE(definition_sl, '') AS definition_sl,
                COALESCE(example_sent, '') AS example_sent,
                COALESCE(created_at, '') AS created_at,
                COALESCE(created_by, '') AS created_by,
                COALESCE(created_by_name, '') AS created_by_name,
                COALESCE(edited_at, '') AS edited_at,
                COALESCE(grade, '') AS grade
            FROM words
            WHERE (word = :exact OR word_sl = :exact)
            " . ($grade > 0 ? " AND grade = :g" : "")
        );
        
        $paramsExact = [':exact' => $q];
        if ($grade > 0) $paramsExact[':g'] = $grade;
        
        $stmtExact->execute($paramsExact);
        $exactRows = $stmtExact->fetchAll(PDO::FETCH_ASSOC);

        //  If exact matches found, return them immediately
        if (!empty($exactRows)) {
            echo json_encode($exactRows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }
    }

    //  Otherwise do a broader search (LIKE)
    $sql = "
        SELECT 
            id,
            COALESCE(word, '') AS word,
            COALESCE(word_sl, '') AS word_sl,
            COALESCE(definition, '') AS definition,
            COALESCE(definition_sl, '') AS definition_sl,
            COALESCE(example_sent, '') AS example_sent,
            COALESCE(created_at, '') AS created_at,
            COALESCE(created_by, '') AS created_by,
            COALESCE(created_by_name, '') AS created_by_name,
            COALESCE(edited_at, '') AS edited_at,
            COALESCE(grade, '') AS grade
        FROM words
    ";

    $conditions = [];
    $params = [];

    if ($q !== '') {
        $conditions[] = "(word LIKE :like OR definition LIKE :like OR word_sl LIKE :like OR definition_sl LIKE :like OR example_sent LIKE :like)";
        $params[':like'] = "%$q%";
    }

    if ($grade > 0) {
        $conditions[] = "grade = :g";
        $params[':g'] = $grade;
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY grade ASC, word";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
