<?php
require_once 'auth.php';

$jsonData = file_get_contents('../data/products.json');
$data = json_decode($jsonData, true);

$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? 'allProducts';
$product = null;

// Find product if editing
if ($id) {
    foreach ($data[$type] as $p) {
        if ($p['id'] == $id) {
            $product = $p;
            break;
        }
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $arabicName = $_POST['arabicName'];
    $price = $_POST['price'];
    $oldPrice = $_POST['oldPrice'] ?: null;
    $category = $_POST['category'];
    $targetType = $_POST['type']; // newArrivals, allProducts, promoPacks
    
    // Image Upload
    $imagePath = $_POST['current_image'] ?? '';
    
    // Debug info (remove in production)
    // error_log(print_r($_FILES, true));

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = __DIR__ . '/../uploads/';
        
        // Ensure directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
            $targetFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = '/uploads/' . $fileName;
            } else {
                // Handle upload error
                $error = "Erreur lors du téléchargement de l'image.";
            }
        } else {
            $error = "Format d'image non supporté.";
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== 4) {
        // Error other than "no file uploaded"
        $error = "Erreur upload code: " . $_FILES['image']['error'];
    }

    // Handle Variants
    $variants = [];
    if (isset($_POST['variant_name'])) {
        foreach ($_POST['variant_name'] as $index => $vName) {
            if (empty($vName)) continue;
            
            $vColor = $_POST['variant_color'][$index] ?? '#000000';
            $vImage = $_POST['variant_current_image'][$index] ?? '';

            // Handle Variant Image Upload
            if (isset($_FILES['variant_image']['name'][$index]) && $_FILES['variant_image']['error'][$index] === 0) {
                $uploadDir = __DIR__ . '/../uploads/';
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $vExt = strtolower(pathinfo($_FILES['variant_image']['name'][$index], PATHINFO_EXTENSION));
                if (in_array($vExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $vFileName = time() . '_v' . $index . '_' . uniqid() . '.' . $vExt;
                    if (move_uploaded_file($_FILES['variant_image']['tmp_name'][$index], $uploadDir . $vFileName)) {
                        $vImage = '/uploads/' . $vFileName;
                    }
                }
            }

            $variants[] = [
                'name' => $vName,
                'color' => $vColor,
                'image' => $vImage
            ];
        }
    }

    $newProduct = [
        'id' => $id ? (int)$id : time(), // Simple ID generation
        'name' => $name,
        'arabicName' => $arabicName,
        'image' => $imagePath,
        'category' => $category,
        'variants' => $variants
    ];

    if ($targetType === 'promoPacks') {
        $newProduct['newPrice'] = (int)$price;
        $newProduct['oldPrice'] = (int)$oldPrice;
        $newProduct['description'] = $_POST['description'] ?? '';
    } else {
        $newProduct['price'] = (int)$price;
        $newProduct['oldPrice'] = $oldPrice ? (int)$oldPrice : null;
    }

    // Update Data
    if (!isset($error)) {
        if ($id) {
            // Edit existing
            $found = false;
            foreach ($data[$targetType] as &$p) {
                if ($p['id'] == $id) {
                    // Merge to keep variants if we didn't edit them
                    $newProduct['variants'] = $p['variants']; 
                    $p = $newProduct;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                // Fallback: maybe the type was wrong? Try searching all types?
                // For now, just error out or do nothing
            }
        } else {
            // Add new
            $data[$targetType][] = $newProduct;
        }

        file_put_contents('../data/products.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Modifier' : 'Ajouter'; ?> Produit - Swis Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold"><?php echo $id ? 'Modifier le Produit' : 'Nouveau Produit'; ?></h1>
            <a href="index.php" class="text-gray-500 hover:text-gray-700">Annuler</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="current_image" value="<?php echo $product['image'] ?? ''; ?>">
            <?php if($id): ?>
                <input type="hidden" name="type" value="<?php echo $type; ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-6">
                <!-- Section Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Section du Site</label>
                    <select name="<?php echo $id ? 'type_display' : 'type'; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2" <?php echo $id ? 'disabled style="background-color: #f3f4f6;"' : ''; ?>>
                        <option value="allProducts" <?php echo $type === 'allProducts' ? 'selected' : ''; ?>>Collection Principale</option>
                        <option value="newArrivals" <?php echo $type === 'newArrivals' ? 'selected' : ''; ?>>Nouveautés</option>
                        <option value="promoPacks" <?php echo $type === 'promoPacks' ? 'selected' : ''; ?>>Packs Promo</option>
                    </select>
                    <?php if($id): ?>
                        <p class="text-xs text-gray-500 mt-1">La section ne peut pas être modifiée lors de l'édition.</p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom du Produit</label>
                    <input type="text" name="name" required value="<?php echo $product['name'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom en Arabe</label>
                    <input type="text" name="arabicName" required value="<?php echo $product['arabicName'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2 text-right" dir="rtl">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prix (DH)</label>
                        <input type="number" name="price" required value="<?php echo $type === 'promoPacks' ? ($product['newPrice'] ?? '') : ($product['price'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ancien Prix (Optionnel)</label>
                        <input type="number" name="oldPrice" value="<?php echo $product['oldPrice'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Catégorie (Label)</label>
                    <div class="relative">
                        <input type="text" name="category" list="category-suggestions" value="<?php echo $product['category'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2" placeholder="Ex: Classique, Nouveau, Pack Promo">
                        <datalist id="category-suggestions">
                            <option value="Classique">
                            <option value="Moderne">
                            <option value="Luxe">
                            <option value="Sport">
                            <option value="Soirée">
                            <option value="Bureau">
                            <option value="Nouveau">
                            <option value="Pack Promo">
                        </datalist>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Sélectionnez une catégorie existante ou tapez-en une nouvelle.</p>
                </div>

                <?php if ($type === 'promoPacks'): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (Pour les packs)</label>
                    <textarea name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2"><?php echo $product['description'] ?? ''; ?></textarea>
                </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Image Principale</label>
                    <?php if (isset($product['image'])): ?>
                        <img src="<?php echo $product['image']; ?>" class="h-20 w-20 object-cover mb-2 rounded border">
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Laisser vide pour conserver l'image actuelle.</p>
                </div>

                <!-- Variants Section -->
                <div class="border-t pt-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Variantes (Couleurs)</h3>
                        <button type="button" onclick="addVariant()" class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded hover:bg-green-200">
                            + Ajouter une variante
                        </button>
                    </div>
                    
                    <div id="variants-container" class="space-y-4">
                        <?php 
                        $variants = $product['variants'] ?? [];
                        foreach($variants as $index => $variant): 
                        ?>
                        <div class="variant-row flex gap-4 items-start bg-gray-50 p-4 rounded border relative">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                            
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Nom (ex: Gold)</label>
                                <input type="text" name="variant_name[]" value="<?php echo htmlspecialchars($variant['name']); ?>" class="w-full rounded border-gray-300 text-sm p-2 border">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Couleur</label>
                                <input type="color" name="variant_color[]" value="<?php echo htmlspecialchars($variant['color'] ?? '#000000'); ?>" class="h-9 w-16 rounded border border-gray-300 p-1">
                            </div>
                            
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Image Spécifique</label>
                                <input type="hidden" name="variant_current_image[]" value="<?php echo htmlspecialchars($variant['image'] ?? ''); ?>">
                                <?php if(!empty($variant['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($variant['image']); ?>" class="h-8 w-8 object-cover mb-1 rounded border inline-block">
                                <?php endif; ?>
                                <input type="file" name="variant_image[]" accept="image/*" class="block w-full text-xs text-gray-500">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <?php echo $id ? 'Enregistrer les modifications' : 'Ajouter le produit'; ?>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function addVariant() {
            const container = document.getElementById('variants-container');
            const div = document.createElement('div');
            div.className = 'variant-row flex gap-4 items-start bg-gray-50 p-4 rounded border relative';
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                    <i class="fas fa-times"></i>
                </button>
                
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Nom (ex: Gold)</label>
                    <input type="text" name="variant_name[]" class="w-full rounded border-gray-300 text-sm p-2 border" placeholder="Nom de la couleur">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Couleur</label>
                    <input type="color" name="variant_color[]" value="#000000" class="h-9 w-16 rounded border border-gray-300 p-1">
                </div>
                
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Image Spécifique</label>
                    <input type="hidden" name="variant_current_image[]" value="">
                    <input type="file" name="variant_image[]" accept="image/*" class="block w-full text-xs text-gray-500">
                </div>
            `;
            container.appendChild(div);
        }
    </script>
</body>
</html>