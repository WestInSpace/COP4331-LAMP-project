<?php
// ============================================================
//  api/index.php — Unified Contacts Manager RESTful API
//
//  GET    /api/index.php?ping=1   — status ping health check
//  POST   /api/index.php (login)  — authenticate user
//  GET    /api/index.php          — list all contacts for user
//  GET    /api/index.php?q=term   — partial search contacts
//  GET    /api/index.php?id=1     — get single contact by ID
//  POST   /api/index.php (contact)  — create new contact
//  PUT    /api/index.php?id=1     — update contact by ID
//  DELETE /api/index.php?id=1     — delete contact by ID
// ============================================================

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

setCORSHeaders();

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

// 1. Unauthenticated Health Check (Ping)
if ($method === 'GET' && (isset($_GET['ping']) || (isset($_GET['action']) && $_GET['action'] === 'ping'))) {
    respond(200, ['status' => 'OK', 'timestamp' => time()]);
}

// 2. Unauthenticated Login (POST with login & password in body)
if ($method === 'POST') {
    $body = getRequestBody();

    if (isset($body['login']) && isset($body['password'])) {
        $login    = clean($body['login']);
        $password = clean($body['password']);

        if (!$login || !$password) {
            respond(400, ['error' => 'Login and password are required']);
        }

		try{
        	$stmt = $db->prepare('SELECT ID, FirstName, LastName, Role, IsDisabled FROM Users WHERE Login = :login AND Password = :password LIMIT 1');
        	$stmt->execute([':login' => $login, ':password' => $password]);
        	$user = $stmt->fetch();
		} catch(Throwable $e){
			respond(500, ['php_error' => $e->getMessage()]);
		}

        if ($user) {
            respond(200, [
                'id'        => (int) $user['ID'],
                'firstName' => $user['FirstName'],
                'lastName'  => $user['LastName'],
				'role'		=> $user['Role'],
				'disabled'	=> $user['IsDisabled'],
                'token'     => (string) $user['ID'],
                'error'     => ''
            ]);
        } else {
            respond(401, [
                'id'        => 0,
                'firstName' => '',
                'lastName'  => '',
				'role'		=> '',
				'disabled'	=> null,
                'error'     => 'Invalid login or password'
            ]);
        }
    }
}

// 3. All other routes require an authenticated user
$userId = requireAuth();

switch ($method) {

    // ── GET: search, list, or single contact ──────────────────
    case 'GET':
        $id     = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $search = isset($_GET['q'])  ? trim($_GET['q'])  : (isset($_GET['search']) ? trim($_GET['search']) : null);
		
		// Temporary debug check
        //respond(200, ['debug_search' => $search, 'debug_uid' => (int)$userId]);

        // Single contact by ID
        if ($id) {
            $stmt = $db->prepare('SELECT ID as id, FirstName as name, LastName, Phone, Email, UserID as user_id FROM Contacts WHERE ID = :id AND UserID = :uid LIMIT 1');
            $stmt->execute([':id' => $id, ':uid' => $userId]);
            $contact = $stmt->fetch();
            if (!$contact) {
                respond(404, ['error' => 'Contact not found']);
            }
            respond(200, $contact);
        }

        // Search contacts (partial match)
        if ($search !== null && $search !== '') {
            $like = '%' . $search . '%';
            $stmt = $db->prepare('SELECT ID as id, FirstName as name, LastName, Phone, Email FROM Contacts WHERE UserID = :uid AND FirstName LIKE :q ORDER BY FirstName');
            $stmt->execute([':uid' => $userId, ':q' => $like]);
            $rows = $stmt->fetchAll();
            $results = array_column($rows, 'name');
            if (empty($results)) {
                respond(200, ['results' => [], 'contacts' => [], 'error' => 'No Records Found']);
            }
            respond(200, ['results' => $results, 'contacts' => $rows, 'error' => '']);
        }

        // List all contacts
        $stmt = $db->prepare('SELECT ID as id, FirstName as name, LastName, Phone, Email FROM Contacts WHERE UserID = :uid ORDER BY FirstName');
        $stmt->execute([':uid' => $userId]);
        $rows = $stmt->fetchAll();
        $results = array_column($rows, 'name');
        if (empty($results)) {
            respond(200, ['results' => [], 'contacts' => [], 'error' => 'No Records Found']);
        }
        respond(200, ['results' => $results, 'contacts' => $rows, 'error' => '']);
        break;

    // ── POST: create contacts ───────────────────────────────────
    case 'POST':
        $body  = getRequestBody();
        
		$firstName = clean($body['firstName'] ?? '');
		$lastName  = clean($body['lastName'] ?? '');
		$phone     = clean($body['phone'] ?? '');
		$email     = clean($body['email'] ?? '');

        if (!$firstName && !$lastName) {
            respond(400, ['error' => 'First name or last name is required']);
        }

        $stmt = $db->prepare('INSERT INTO Contacts (UserID, FirstName,  Lastname, Phone, Email) VALUES (:uid, :firstName, :lastName, :phone, :email)');
        $stmt->execute([':uid' => $userId, ':firstName' => $firstName, ':lastName' => $lastName, ':phone' => $phone, ':email' => $email]);

        respond(201, [
            'message' => 'Contact created',
            'id'      => (int) $db->lastInsertId(),
            'error'   => ''
        ]);
        break;

    // ── PUT: update contact ─────────────────────────────────────
    case 'PUT':
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if (!$id) {
            respond(400, ['error' => 'Contact ID is required — use ?id=']);
        }

        $check = $db->prepare('SELECT ID FROM Contacts WHERE ID = :id AND UserID = :uid LIMIT 1');
        $check->execute([':id' => $id, ':uid' => $userId]);
        if (!$check->fetch()) {
            respond(404, ['error' => 'Contact not found']);
        }

        $body = getRequestBody();
        $firstName = clean($body['firstName'] ?? '');
		$lastName  = clean($body['lastName'] ?? '');
		$phone     = clean($body['phone'] ?? '');
		$email     = clean($body['email'] ?? '');

        if (!$firstName && !$lastName) {
            respond(400, ['error' => 'firstName or lastName is required']);
        }

        $stmt = $db->prepare('UPDATE Contacts SET FirstName = :firstName, LastName = :lastName, Phone = :phone, Email = :email WHERE ID = :id AND UserID = :uid');
        $stmt->execute([':firstName' => $firstName, ':lastName' => $lastName, ':phone' => $phone, ':email' => $email, ':id' => $id, ':uid' => $userId]);

        respond(200, ['message' => 'Contact updated', 'error' => '']);
        break;

    // ── DELETE: delete contact ──────────────────────────────────
    case 'DELETE':
        $id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $firstName = isset($_GET['firstName']) ? clean($_GET['firstName']) : '';
        $lastName = isset($_GET['lastName']) ? clean($_GET['lastName']) : '';

        if ($id > 0) {
            $stmt = $db->prepare('DELETE FROM Contacts WHERE ID = :id AND UserID = :uid');
            $stmt->execute([':id' => $id, ':uid' => $userId]);
        } elseif ($name !== '') {
            $stmt = $db->prepare('DELETE FROM Contacts WHERE FirstName = :firstName AND LastName = :lastName AND UserID = :uid LIMIT 1');
            $stmt->execute([':firstName' => $firstName, ':lastName' => $lastName, ':uid' => $userId]);
        } else {
            respond(400, ['error' => 'Contact ID or Name is required — use ?id= or ?name=']);
        }

        if ($stmt->rowCount() === 0) {
            respond(404, ['error' => 'Contact not found']);
        }

        respond(200, ['message' => 'Contact deleted', 'error' => '']);
        break;

    default:
        respond(405, ['error' => 'Method not allowed']);
}
