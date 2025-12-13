<?php
/**
 * Swis Brands - Site Settings Admin
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

require_once __DIR__ . '/../includes/admin-bootstrap.php';

$settings = getSettings();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCsrfToken()) {
        $error = 'Session expirée, veuillez réessayer.';
    } else {
        $newSettings = [
            'whatsapp_number' => preg_replace('/[^0-9]/', '', $_POST['whatsapp_number'] ?? ''),
            'site_title' => sanitizeStrict($_POST['site_title'] ?? ''),
            'hero_title' => sanitizeStrict($_POST['hero_title'] ?? ''),
            'hero_subtitle' => sanitizeStrict($_POST['hero_subtitle'] ?? ''),
            'currency' => sanitizeStrict($_POST['currency'] ?? 'DH')
        ];
        
        if (saveJsonFile(SETTINGS_FILE, $newSettings)) {
            $success = "Paramètres mis à jour avec succès !";
            $settings = $newSettings;
        } else {
            $error = "Erreur lors de la sauvegarde.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Swis Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="index.php" class="font-bold text-xl text-gray-800">Swis Admin</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="tracking.php" class="text-purple-600 hover:text-purple-800"><i class="fas fa-chart-line mr-1"></i> Tracking</a>
                    <a href="index.php" class="text-gray-500 hover:text-gray-700">Retour au Dashboard</a>
                    <a href="logout.php" class="text-red-500 hover:text-red-700">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Paramètres du Site</h2>
            
            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <?php echo csrfField(); ?>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contact & WhatsApp</h3>
                        <label class="block text-sm font-medium text-gray-700">Numéro WhatsApp (Format International)</label>
                        <input type="text" name="whatsapp_number" value="<?php echo htmlspecialchars($settings['whatsapp_number']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2" placeholder="2126XXXXXXXX">
                        <p class="text-xs text-gray-500 mt-1">C'est le numéro qui recevra les commandes.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Affichage Site</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Titre du Site (Onglet Navigateur)</label>
                                <input type="text" name="site_title" value="<?php echo htmlspecialchars($settings['site_title']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Titre Principal (Hero)</label>
                                <input type="text" name="hero_title" value="<?php echo htmlspecialchars($settings['hero_title']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sous-titre (Hero)</label>
                                <input type="text" name="hero_subtitle" value="<?php echo htmlspecialchars($settings['hero_subtitle']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700">Devise</label>
                                <input type="text" name="currency" value="<?php echo htmlspecialchars($settings['currency']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Enregistrer les paramètres
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>