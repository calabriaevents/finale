<?php
/**
 * Sezione Esperienze dei Visitatori
 * Mostra le foto approvate degli utenti per un articolo o provincia
 * 
 * Parametri richiesti:
 * $article_id (int) - ID dell'articolo (opzionale se $province_id è fornito)
 * $province_id (int) - ID della provincia (opzionale se $article_id è fornito)
 * $db (Database) - Istanza del database
 */

// Validazione parametri
if (empty($article_id) && empty($province_id)) {
    return; // Non mostrare nulla se nessun parametro è fornito
}

// Query per ottenere le esperienze approvate
$where_conditions = ["u.status = 'approved'"];
$params = [];
$param_types = '';

if (!empty($article_id)) {
    $where_conditions[] = "u.article_id = ?";
    $params[] = intval($article_id);
    $param_types .= 'i';
}

if (!empty($province_id)) {
    $where_conditions[] = "(u.province_id = ? OR a.province_id = ?)";
    $params[] = intval($province_id);
    $params[] = intval($province_id);
    $param_types .= 'ii';
}

$where_clause = implode(' AND ', $where_conditions);

$query = "
    SELECT u.*, 
           a.title as article_title, 
           a.slug as article_slug,
           p.name as province_name
    FROM user_uploads u
    LEFT JOIN articles a ON u.article_id = a.id
    LEFT JOIN provinces p ON u.province_id = p.id
    WHERE {$where_clause}
    ORDER BY u.created_at DESC
    LIMIT 12
";

$stmt = $db->connection->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$experiences = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Se non ci sono esperienze, non mostrare la sezione
if (empty($experiences)) {
    return;
}

// Conta il totale per il link "Vedi tutte"
$count_query = str_replace("SELECT u.*, a.title as article_title, a.slug as article_slug, p.name as province_name", "SELECT COUNT(*)", $query);
$count_query = str_replace("ORDER BY u.created_at DESC LIMIT 12", "", $count_query);

$count_stmt = $db->connection->prepare($count_query);
if (!empty($params)) {
    $count_stmt->bind_param($param_types, ...$params);
}
$count_stmt->execute();
$total_count = $count_stmt->get_result()->fetch_row()[0];
?>

<!-- Sezione Esperienze dei Visitatori -->
<section class="py-16 bg-gray-50" id="user-experiences">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6">
                <i data-lucide="camera" class="w-8 h-8 text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                📸 Le Esperienze dei Visitatori
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Scopri le foto e le storie di chi ha già vissuto questa esperienza
            </p>
        </div>

        <!-- Upload Button -->
        <div class="text-center mb-12">
            <button onclick="openUploadModal(<?php echo $article_id ? $article_id : 'null'; ?>, <?php echo $province_id ? $province_id : 'null'; ?>)" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i data-lucide="upload" class="w-5 h-5 mr-2"></i>
                Condividi la Tua Esperienza
            </button>
            <p class="mt-2 text-sm text-gray-500">
                Hai visitato questo posto? Carica la tua foto e racconta la tua storia!
            </p>
        </div>

        <!-- Experiences Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <?php foreach ($experiences as $experience): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
                <!-- Image -->
                <div class="relative aspect-w-4 aspect-h-3 overflow-hidden">
                    <img src="<?php echo htmlspecialchars($experience['image_path']); ?>" 
                         alt="Esperienza di <?php echo htmlspecialchars($experience['user_name']); ?>"
                         class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500"
                         loading="lazy">
                    
                    <!-- Overlay con nome utente -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-4 left-4 right-4">
                            <p class="text-white font-semibold text-sm truncate">
                                📷 <?php echo htmlspecialchars($experience['user_name']); ?>
                            </p>
                            <?php if ($experience['article_title']): ?>
                            <p class="text-white/80 text-xs truncate mt-1">
                                <?php echo htmlspecialchars($experience['article_title']); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <!-- User Info -->
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            <?php echo strtoupper(substr($experience['user_name'], 0, 1)); ?>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">
                                <?php echo htmlspecialchars($experience['user_name']); ?>
                            </p>
                            <p class="text-xs text-gray-500">
                                <?php echo date('d M Y', strtotime($experience['created_at'])); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            <?php 
                            $description = htmlspecialchars($experience['description']);
                            echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                            ?>
                        </p>
                    </div>

                    <!-- View Full Button -->
                    <button onclick="openExperienceModal(<?php echo htmlspecialchars(json_encode($experience)); ?>)"
                            class="w-full text-center py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                        Leggi di più
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- View All Button (if more than 12 experiences) -->
        <?php if ($total_count > 12): ?>
        <div class="text-center">
            <a href="#" onclick="loadMoreExperiences(); return false;" 
               class="inline-flex items-center px-6 py-3 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-medium rounded-lg transition-all duration-200">
                <i data-lucide="grid" class="w-5 h-5 mr-2"></i>
                Vedi Tutte le <?php echo $total_count; ?> Esperienze
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Modal per visualizzare l'esperienza completa -->
<div id="experienceModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="relative">
            <!-- Close Button -->
            <button onclick="closeExperienceModal()" 
                    class="absolute top-4 right-4 z-10 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center text-gray-600 hover:text-gray-800 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            
            <!-- Modal Content -->
            <div id="experienceModalContent">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
// Experience Modal Functions
function openExperienceModal(experience) {
    const modal = document.getElementById('experienceModal');
    const content = document.getElementById('experienceModalContent');
    
    content.innerHTML = `
        <!-- Image -->
        <div class="relative">
            <img src="${experience.image_path}" 
                 alt="Esperienza di ${experience.user_name}"
                 class="w-full h-64 md:h-80 object-cover">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                <div class="flex items-center text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                        ${experience.user_name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">${experience.user_name}</h3>
                        <p class="text-white/80 text-sm">${new Date(experience.created_at).toLocaleDateString('it-IT', {
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric'
                        })}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            ${experience.article_title ? `
            <div class="mb-4">
                <p class="text-sm text-blue-600 font-medium">📄 ${experience.article_title}</p>
            </div>
            ` : ''}
            
            <div class="prose prose-gray max-w-none">
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">${experience.description}</p>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Foto condivisa il ${new Date(experience.created_at).toLocaleDateString('it-IT')} 
                    ${experience.province_name ? `da ${experience.province_name}` : ''}
                </p>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeExperienceModal() {
    const modal = document.getElementById('experienceModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function loadMoreExperiences() {
    // TODO: Implementare caricamento di più esperienze con AJAX
    alert('Funzione in sviluppo: caricamento di tutte le esperienze');
}

// Close modal when clicking outside
document.getElementById('experienceModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeExperienceModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('experienceModal').classList.contains('hidden')) {
        closeExperienceModal();
    }
});
</script>