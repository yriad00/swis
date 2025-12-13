<?php
/**
 * Swis Brands - Admin Dashboard
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

require_once __DIR__ . '/../includes/admin-bootstrap.php';

// Ensure correct base URL for relative links if accessed without trailing slash
if (basename($_SERVER['PHP_SELF']) == 'index.php' && 
    strpos($_SERVER['REQUEST_URI'], '.php') === false && 
    substr($_SERVER['REQUEST_URI'], -1) != '/') {
    redirect($_SERVER['REQUEST_URI'] . "/");
}

$data = getProductsData();
$newArrivals = $data['newArrivals'];
$allProducts = $data['allProducts'];
$promoPacks = $data['promoPacks'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Swis Brands</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="font-bold text-xl text-gray-800">Swis Admin</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="../index.php" target="_blank" class="text-gray-500 hover:text-gray-700 mr-4">Voir le site</a>
                    <a href="logout.php" class="text-red-500 hover:text-red-700">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Produits</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo count($allProducts); ?></dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Nouveautés</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo count($newArrivals); ?></dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Packs Promo</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900"><?php echo count($promoPacks); ?></dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg cursor-pointer hover:bg-gray-50 transition" onclick="window.location.href='settings.php'">
                <div class="px-4 py-5 sm:p-6 flex flex-col items-center justify-center h-full">
                    <i class="fas fa-cog text-3xl text-gray-400 mb-2"></i>
                    <dt class="text-sm font-medium text-gray-500 truncate">Paramètres</dt>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg cursor-pointer hover:bg-purple-50 transition" onclick="window.location.href='tracking.php'">
                <div class="px-4 py-5 sm:p-6 flex flex-col items-center justify-center h-full">
                    <i class="fas fa-chart-line text-3xl text-purple-400 mb-2"></i>
                    <dt class="text-sm font-medium text-gray-500 truncate">Tracking & Pixels</dt>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Gestion des Produits</h3>
                <a href="product_form.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i> Ajouter
                </a>
            </div>
            
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button onclick="switchTab('collection')" id="tab-collection" class="tab-btn w-1/3 py-4 px-1 text-center border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        Collection Principale
                    </button>
                    <button onclick="switchTab('new')" id="tab-new" class="tab-btn w-1/3 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Nouveautés
                    </button>
                    <button onclick="switchTab('packs')" id="tab-packs" class="tab-btn w-1/3 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Packs Promo
                    </button>
                </nav>
            </div>

            <div class="p-4">
                <!-- Collection Table -->
                <div id="content-collection" class="tab-content">
                    <?php renderTable($allProducts, 'allProducts'); ?>
                </div>
                <!-- New Arrivals Table -->
                <div id="content-new" class="tab-content hidden">
                    <?php renderTable($newArrivals, 'newArrivals'); ?>
                </div>
                <!-- Packs Table -->
                <div id="content-packs" class="tab-content hidden">
                    <?php renderTable($promoPacks, 'promoPacks'); ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Show selected content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Reset tabs
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-blue-500', 'text-blue-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            // Highlight selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }

        function deleteProduct(id, type, csrfToken) {
            if(confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                // Create and submit a form with POST method
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'delete_product.php';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                form.appendChild(idInput);
                
                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'type';
                typeInput.value = type;
                form.appendChild(typeInput);
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>

<?php
function renderTable($products, $type) {
    $csrfToken = $_SESSION['csrf_token'];
    echo '<div class="overflow-x-auto">';
    echo '<table class="min-w-full divide-y divide-gray-200">';
    echo '<thead class="bg-gray-50"><tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr></thead>';
    echo '<tbody class="bg-white divide-y divide-gray-200">';
    
    foreach ($products as $product) {
        $price = isset($product['newPrice']) ? $product['newPrice'] : $product['price'];
        echo '<tr>';
        echo '<td class="px-6 py-4 whitespace-nowrap"><img src="' . htmlspecialchars($product['image']) . '" class="h-10 w-10 rounded-full object-cover"></td>';
        echo '<td class="px-6 py-4 whitespace-nowrap"><div class="text-sm font-medium text-gray-900">' . htmlspecialchars($product['name']) . '</div><div class="text-sm text-gray-500">' . htmlspecialchars($product['arabicName']) . '</div></td>';
        echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">' . $price . ' DH</td>';
        echo '<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">';
        echo '<a href="product_form.php?id=' . $product['id'] . '&type=' . $type . '" class="text-indigo-600 hover:text-indigo-900 mr-4">Modifier</a>';
        echo '<button onclick="deleteProduct(' . $product['id'] . ', \'' . $type . '\', \'' . $csrfToken . '\')" class="text-red-600 hover:text-red-900">Supprimer</button>';
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</tbody></table></div>';
}
?>