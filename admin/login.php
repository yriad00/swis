<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

// Load admin credentials from secure file (create this file with hashed password)
$credentialsFile = __DIR__ . '/../data/admin_credentials.json';
if (!file_exists($credentialsFile)) {
    // Default credentials - CHANGE IMMEDIATELY after first login
    $defaultCredentials = [
        'username' => 'admin',
        'password_hash' => password_hash('admin123', PASSWORD_DEFAULT)
    ];
    file_put_contents($credentialsFile, json_encode($defaultCredentials, JSON_PRETTY_PRINT));
}
$credentials = json_decode(file_get_contents($credentialsFile), true);

$error = '';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Session expirée, veuillez réessayer.';
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Use secure password verification
        if ($username === $credentials['username'] && password_verify($password, $credentials['password_hash'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // New token after login
            header('Location: index.php');
            exit;
        } else {
            $error = 'Identifiants incorrects';
            // Add small delay to prevent brute force
            usleep(500000); // 0.5 seconds
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Swis Brands</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Admin Login</h1>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php 
        // Warning for default credentials
        $credentialsFile = __DIR__ . '/../data/admin_credentials.json';
        if (file_exists($credentialsFile)) {
            $creds = json_decode(file_get_contents($credentialsFile), true);
            if ($creds && $creds['username'] === 'admin' && password_verify('admin123', $creds['password_hash'])) {
                echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <strong>Attention:</strong> Vous utilisez les identifiants par défaut (admin/admin123). Veuillez les changer immédiatement.
                </div>';
            }
        }
        ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Nom d'utilisateur
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" type="text" placeholder="admin">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Mot de passe
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="******************">
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" type="submit">
                    Se connecter
                </button>
            </div>
        </form>
    </div>
</body>
</html>