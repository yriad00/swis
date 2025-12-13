<?php
/**
 * Swis Brands - Tracking Settings Admin
 * Manage Facebook Pixel, Google Analytics, and other tracking codes
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

require_once __DIR__ . '/../includes/admin-bootstrap.php';

$tracking = getTrackingConfig();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCsrfToken()) {
        $error = 'Session expirée, veuillez réessayer.';
    } else {
        $newTracking = [
            'facebook_pixel_id' => trim(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['facebook_pixel_id'] ?? '')),
            'google_analytics_id' => trim(preg_replace('/[^a-zA-Z0-9-]/', '', $_POST['google_analytics_id'] ?? '')),
            'google_ads_id' => trim(preg_replace('/[^a-zA-Z0-9-]/', '', $_POST['google_ads_id'] ?? '')),
            'tiktok_pixel_id' => trim(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['tiktok_pixel_id'] ?? '')),
            'snapchat_pixel_id' => trim(preg_replace('/[^a-zA-Z0-9-]/', '', $_POST['snapchat_pixel_id'] ?? '')),
            'custom_head_scripts' => $_POST['custom_head_scripts'] ?? '',
            'custom_body_scripts' => $_POST['custom_body_scripts'] ?? ''
        ];
        
        if (saveJsonFile(TRACKING_FILE, $newTracking)) {
            $success = "Paramètres de tracking mis à jour avec succès !";
            $tracking = $newTracking;
        } else {
            $error = "Erreur lors de la sauvegarde des paramètres.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking & Pixels - Swis Admin</title>
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
                    <a href="settings.php" class="text-gray-500 hover:text-gray-700"><i class="fas fa-cog mr-1"></i> Site</a>
                    <a href="index.php" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                    <a href="logout.php" class="text-red-500 hover:text-red-700">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Tracking & Pixels</h2>
                    <p class="text-gray-500 text-sm mt-1">Gérez vos pixels de suivi et codes analytics</p>
                </div>
                <a href="settings.php" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Retour aux paramètres
                </a>
            </div>
            
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i><?php echo sanitize($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo sanitize($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-8">
                <?php echo csrfField(); ?>
                
                <!-- Facebook Pixel -->
                <div class="border-b pb-6">
                    <div class="flex items-center mb-4">
                        <i class="fab fa-facebook text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Facebook Pixel</h3>
                            <p class="text-xs text-gray-500">Pour le suivi des conversions Facebook/Meta Ads</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pixel ID</label>
                        <input type="text" name="facebook_pixel_id" 
                               value="<?php echo sanitize($tracking['facebook_pixel_id'] ?? ''); ?>" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2"
                               placeholder="Ex: 1234567890123456">
                        <p class="text-xs text-gray-500 mt-1">Trouvez votre ID dans Meta Events Manager</p>
                    </div>
                </div>

                <!-- Google Analytics -->
                <div class="border-b pb-6">
                    <div class="flex items-center mb-4">
                        <i class="fab fa-google text-orange-500 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Google Analytics 4</h3>
                            <p class="text-xs text-gray-500">Pour analyser le trafic de votre site</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Measurement ID</label>
                        <input type="text" name="google_analytics_id" 
                               value="<?php echo sanitize($tracking['google_analytics_id'] ?? ''); ?>" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2"
                               placeholder="Ex: G-XXXXXXXXXX">
                        <p class="text-xs text-gray-500 mt-1">Commence par G- pour GA4</p>
                    </div>
                </div>

                <!-- Google Ads -->
                <div class="border-b pb-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-ad text-yellow-500 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Google Ads</h3>
                            <p class="text-xs text-gray-500">Pour le suivi des conversions Google Ads</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Conversion ID</label>
                        <input type="text" name="google_ads_id" 
                               value="<?php echo sanitize($tracking['google_ads_id'] ?? ''); ?>" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2"
                               placeholder="Ex: AW-XXXXXXXXXX">
                        <p class="text-xs text-gray-500 mt-1">Commence par AW-</p>
                    </div>
                </div>

                <!-- TikTok Pixel -->
                <div class="border-b pb-6">
                    <div class="flex items-center mb-4">
                        <i class="fab fa-tiktok text-black text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">TikTok Pixel</h3>
                            <p class="text-xs text-gray-500">Pour le suivi des conversions TikTok Ads</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pixel ID</label>
                        <input type="text" name="tiktok_pixel_id" 
                               value="<?php echo sanitize($tracking['tiktok_pixel_id'] ?? ''); ?>" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2"
                               placeholder="Ex: XXXXXXXXXXXXXXXXX">
                        <p class="text-xs text-gray-500 mt-1">Trouvez votre ID dans TikTok Ads Manager</p>
                    </div>
                </div>

                <!-- Snapchat Pixel -->
                <div class="border-b pb-6">
                    <div class="flex items-center mb-4">
                        <i class="fab fa-snapchat text-yellow-400 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Snapchat Pixel</h3>
                            <p class="text-xs text-gray-500">Pour le suivi des conversions Snapchat Ads</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pixel ID</label>
                        <input type="text" name="snapchat_pixel_id" 
                               value="<?php echo sanitize($tracking['snapchat_pixel_id'] ?? ''); ?>" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2"
                               placeholder="Ex: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx">
                    </div>
                </div>

                <!-- Custom Scripts -->
                <div class="border-b pb-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-code text-purple-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Scripts Personnalisés</h3>
                            <p class="text-xs text-gray-500">Ajoutez d'autres scripts de tracking (optionnel)</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scripts Head (avant &lt;/head&gt;)</label>
                            <textarea name="custom_head_scripts" rows="4"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2 font-mono text-sm"
                                      placeholder="<!-- Collez votre code ici -->"><?php echo sanitize($tracking['custom_head_scripts'] ?? ''); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Scripts Body (avant &lt;/body&gt;)</label>
                            <textarea name="custom_body_scripts" rows="4"
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2 font-mono text-sm"
                                      placeholder="<!-- Collez votre code ici -->"><?php echo sanitize($tracking['custom_body_scripts'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les paramètres
                    </button>
                </div>
            </form>
            
            <!-- Help Section -->
            <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                <h4 class="font-medium text-blue-800 mb-2"><i class="fas fa-info-circle mr-1"></i> Aide</h4>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Les pixels seront automatiquement insérés dans toutes les pages du site</li>
                    <li>• Les événements AddToCart, Checkout et Purchase sont trackés automatiquement</li>
                    <li>• Testez vos pixels avec les outils de debug de chaque plateforme</li>
                    <li>• Laissez un champ vide pour désactiver ce pixel</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
