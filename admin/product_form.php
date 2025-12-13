<?php
/**
 * Swis Brands - Product Form (Add/Edit)
 * 
 * @package SwissBrands
 * @version 2.0.0
 */

require_once __DIR__ . '/../includes/admin-bootstrap.php';

$data = getProductsData();

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
    // Validate CSRF token
    if (!validateCsrfToken()) {
        $error = "Session expirée, veuillez réessayer.";
    }
    // Check for POST size limit exceeded
    elseif (empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0) {
        $error = "Le fichier envoyé est trop volumineux. Vérifiez la configuration upload_max_filesize et post_max_size.";
    } else {
        $name = trim(strip_tags($_POST['name'] ?? ''));
        $arabicName = trim(strip_tags($_POST['arabicName'] ?? ''));
        $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
        $oldPrice = !empty($_POST['oldPrice']) ? filter_var($_POST['oldPrice'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) : null;
        $category = trim(strip_tags($_POST['category'] ?? ''));
        $targetType = $_POST['type'] ?? 'allProducts';
        
        // Validate targetType against whitelist
        $allowedTypes = ['newArrivals', 'allProducts', 'promoPacks'];
        if (!in_array($targetType, $allowedTypes, true)) {
            $targetType = 'allProducts';
        }
        
        // Validate required fields
        if (empty($name) || $price === false) {
            $error = "Le nom et le prix sont obligatoires.";
        }
        
        // New Fields
        $group = $_POST['group'] ?? null;
        if ($group && !in_array($group, ['A', 'B', 'C'], true)) {
            $group = null;
        }
        
        // Image Upload
        $imagePath = $_POST['current_image'] ?? '';

        // Gallery Upload
        $gallery = [];
        if (isset($_POST['current_gallery'])) {
            $gallery = $_POST['current_gallery'];
        }
        
        if (isset($_FILES['gallery'])) {
            $uploadDir = __DIR__ . '/../uploads/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            
            foreach ($_FILES['gallery']['name'] as $key => $galleryFileName) {
                if ($_FILES['gallery']['error'][$key] === 0) {
                    $ext = strtolower(pathinfo($galleryFileName, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $uploadedFileName = time() . '_g' . $key . '_' . uniqid() . '.' . $ext;
                        if (move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $uploadDir . $uploadedFileName)) {
                            $gallery[] = '/uploads/' . $uploadedFileName;
                        }
                    }
                }
            }
        }

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $uploadDir = __DIR__ . '/../uploads/';
            
            // Ensure directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                // Validate MIME type
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($_FILES['image']['tmp_name']);
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                if (in_array($mimeType, $allowedMimeTypes)) {
                    $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
                    $targetFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $imagePath = '/uploads/' . $fileName;
                    } else {
                        $error = "Erreur lors du téléchargement de l'image.";
                    }
                } else {
                    $error = "Type de fichier invalide (MIME type mismatch).";
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
                
                // Get existing variant gallery
                $vGallery = [];
                if (isset($_POST['variant_current_gallery'][$index])) {
                    $vGallery = $_POST['variant_current_gallery'][$index];
                    if (!is_array($vGallery)) $vGallery = [];
                }

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
                
                // Handle Variant Gallery Upload
                if (isset($_FILES['variant_gallery']['name'][$index])) {
                    $uploadDir = __DIR__ . '/../uploads/';
                    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
                    
                    foreach ($_FILES['variant_gallery']['name'][$index] as $gKey => $gName) {
                        if ($_FILES['variant_gallery']['error'][$index][$gKey] === 0) {
                            $gExt = strtolower(pathinfo($gName, PATHINFO_EXTENSION));
                            if (in_array($gExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                $gFileName = time() . '_vg' . $index . '_' . $gKey . '_' . uniqid() . '.' . $gExt;
                                if (move_uploaded_file($_FILES['variant_gallery']['tmp_name'][$index][$gKey], $uploadDir . $gFileName)) {
                                    $vGallery[] = '/uploads/' . $gFileName;
                                }
                            }
                        }
                    }
                }

                $variant = [
                    'name' => $vName,
                    'color' => $vColor,
                    'image' => $vImage
                ];
                
                // Only add gallery if it has images
                if (!empty($vGallery)) {
                    $variant['gallery'] = $vGallery;
                }
                
                $variants[] = $variant;
            }
        }

        $newProduct = [
            'id' => $id ? (int)$id : time(), // Simple ID generation
            'name' => $name,
            'arabicName' => $arabicName,
            'image' => $imagePath,
            'gallery' => $gallery,
            'category' => $category,
            'variants' => $variants,
            'price' => (int)$price,
            'oldPrice' => $oldPrice ? (int)$oldPrice : null
        ];

        // Add group only for non-pack products
        if ($targetType !== 'promoPacks' && $group) {
            $newProduct['group'] = $group;
        }

        // Update Data
        if (!isset($error)) {
            if ($id) {
                // Edit existing
                $found = false;
                foreach ($data[$targetType] as &$p) {
                    if ($p['id'] == $id) {
                        // Check if image has changed and delete old one
                        if ($newProduct['image'] !== $p['image'] && !empty($p['image'])) {
                            $oldImagePath = __DIR__ . '/..' . $p['image'];
                            if (file_exists($oldImagePath)) {
                                unlink($oldImagePath);
                            }
                        }
                        
                        $p = $newProduct;
                        $found = true;
                        break;
                    }
                }
            } else {
                // Add new
                $data[$targetType][] = $newProduct;
            }

            file_put_contents('../data/products.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            header('Location: index.php');
            exit;
        }
    } // End of else block for POST size check
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
            <?php echo csrfField(); ?>
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image'] ?? ''); ?>">
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
                    <label class="block text-sm font-medium text-gray-700">Nom du Produit <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2" minlength="2">
                    <p class="text-xs text-gray-500 mt-1">Le nom est obligatoire et ne peut pas être vide.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom en Arabe</label>
                    <input type="text" name="arabicName" required value="<?php echo htmlspecialchars($product['arabicName'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2 text-right" dir="rtl">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Prix (DH)</label>
                        <input type="number" name="price" required value="<?php echo $product['price'] ?? $product['newPrice'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ancien Prix (Optionnel)</label>
                        <input type="number" name="oldPrice" value="<?php echo $product['oldPrice'] ?? ''; ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Catégorie (Label)</label>
                    <div class="relative">
                        <input type="text" name="category" list="category-suggestions" value="<?php echo htmlspecialchars($product['category'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2" placeholder="Ex: Classique, Nouveau, Pack Promo">
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

                <?php if ($type !== 'promoPacks'): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Groupe (Pour les Packs)</label>
                    <select name="group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 border p-2">
                        <option value="">Aucun</option>
                        <option value="A" <?php echo ($product['group'] ?? '') === 'A' ? 'selected' : ''; ?>>Groupe A (Basic)</option>
                        <option value="B" <?php echo ($product['group'] ?? '') === 'B' ? 'selected' : ''; ?>>Groupe B (Premium)</option>
                        <option value="C" <?php echo ($product['group'] ?? '') === 'C' ? 'selected' : ''; ?>>Groupe C (Luxe)</option>
                    </select>
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

                <div>
                    <label class="block text-sm font-medium text-gray-700">Galerie d'images (Optionnel)</label>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <?php 
                        $gallery = $product['gallery'] ?? [];
                        foreach($gallery as $img): 
                        ?>
                            <div class="relative">
                                <img src="<?php echo $img; ?>" class="h-16 w-16 object-cover rounded border">
                                <input type="hidden" name="current_gallery[]" value="<?php echo $img; ?>">
                                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">x</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <input type="file" name="gallery[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Vous pouvez sélectionner plusieurs images.</p>
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
                        <div class="variant-row bg-gray-50 p-4 rounded border relative">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Nom (ex: Gold)</label>
                                    <input type="text" name="variant_name[]" value="<?php echo htmlspecialchars($variant['name']); ?>" class="w-full rounded border-gray-300 text-sm p-2 border">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Couleur</label>
                                    <input type="color" name="variant_color[]" value="<?php echo htmlspecialchars($variant['color'] ?? '#000000'); ?>" class="h-9 w-full rounded border border-gray-300 p-1">
                                </div>
                            
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Image Principale</label>
                                    <input type="hidden" name="variant_current_image[]" value="<?php echo htmlspecialchars($variant['image'] ?? ''); ?>">
                                    <div class="flex items-center gap-2">
                                        <?php if(!empty($variant['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($variant['image']); ?>" class="h-10 w-10 object-cover rounded border">
                                        <?php endif; ?>
                                        <input type="file" name="variant_image[]" accept="image/*" class="block w-full text-xs text-gray-500">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Variant Gallery -->
                            <div class="border-t pt-3 mt-3">
                                <label class="block text-xs font-bold text-gray-500 mb-2">Galerie de cette variante (optionnel)</label>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <?php 
                                    $variantGallery = $variant['gallery'] ?? [];
                                    foreach($variantGallery as $gImg): 
                                    ?>
                                        <div class="relative">
                                            <img src="<?php echo htmlspecialchars($gImg); ?>" class="h-12 w-12 object-cover rounded border">
                                            <input type="hidden" name="variant_current_gallery[<?php echo $index; ?>][]" value="<?php echo htmlspecialchars($gImg); ?>">
                                            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px]">×</button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="file" name="variant_gallery[<?php echo $index; ?>][]" multiple accept="image/*" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 file:text-gray-600">
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
        let variantCounter = <?php echo count($product['variants'] ?? []); ?>;
        
        function addVariant() {
            const container = document.getElementById('variants-container');
            const index = variantCounter++;
            const div = document.createElement('div');
            div.className = 'variant-row bg-gray-50 p-4 rounded border relative';
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
                    <i class="fas fa-times"></i>
                </button>
                
                <div class="flex gap-4 items-start mb-3">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nom (ex: Gold)</label>
                        <input type="text" name="variant_name[]" class="w-full rounded border-gray-300 text-sm p-2 border" placeholder="Nom de la couleur">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Couleur</label>
                        <input type="color" name="variant_color[]" value="#000000" class="h-9 w-16 rounded border border-gray-300 p-1">
                    </div>
                    
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Image Principale</label>
                        <input type="hidden" name="variant_current_image[]" value="">
                        <input type="file" name="variant_image[]" accept="image/*" class="block w-full text-xs text-gray-500">
                    </div>
                </div>
                
                <!-- Variant Gallery -->
                <div class="border-t pt-3 mt-2">
                    <label class="block text-xs font-bold text-gray-500 mb-2">
                        <i class="fas fa-images mr-1"></i>Galerie de cette variante (optionnel)
                    </label>
                    <input type="hidden" name="variant_current_gallery[${index}]" value="">
                    <input type="file" name="variant_gallery[${index}][]" accept="image/*" multiple class="block w-full text-xs text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700">
                    <p class="text-xs text-gray-400 mt-1">Vous pouvez sélectionner plusieurs images</p>
                </div>
            `;
            container.appendChild(div);
        }
    </script>
</body>
</html>